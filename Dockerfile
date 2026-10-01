FROM php:8.2-apache

# 1. Installation des dépendances système + Node.js 20
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libicu-dev curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql zip bcmath intl \
    && docker-php-ext-enable opcache

# 1b. Réglages PHP de production (OPcache, limites d'upload…)
COPY docker/php.ini "$PHP_INI_DIR/conf.d/zz-app.ini"

# 2. Configuration Apache (rewrite + compression + cache des assets)
RUN a2enmod rewrite deflate expires headers
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# 3. Certificat SSL pour TiDB Cloud
RUN mkdir -p /var/www/html/certs
ADD https://letsencrypt.org/certs/isrgrootx1.pem /var/www/html/certs/isrgrootx1.pem

# 4. Installation des dépendances et Compilation des assets (CSS/JS)
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm install
RUN npm run build

# 5. Configuration Apache et Permissions
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 5b. Compilation du Blade au build (ne dépend pas des variables d'env runtime).
RUN php artisan view:cache

# 6. Au démarrage : cache config + routes (les variables d'env Render sont
#    injectées au runtime), migrations, worker d'e-mails en tâche de fond,
#    puis Apache.
#    migrate --force n'est volontairement PAS bloquant : si la base est
#    temporairement injoignable (ex. quota TiDB épuisé), le conteneur doit
#    quand même démarrer Apache plutôt que de crasher en boucle (Render
#    considère un CMD qui s'arrête comme un échec de l'instance, "Exited
#    with status 1", et la redémarre sans fin tant que la base ne répond
#    pas). Les migrations en attente repasseront au prochain déploiement,
#    ou peuvent être rejouées manuellement une fois la base de nouveau
#    accessible.
#    Worker d'e-mails : une passe par minute avec --stop-when-empty (il vide
#    la file puis s'arrête, ce qui ferme sa connexion TiDB) plutôt qu'un
#    processus permanent. Le conteneur consommait ~27 RU/s en continu sur
#    TiDB alors que les requêtes SQL n'en expliquaient qu'une infime partie,
#    ce qui épuisait le quota gratuit mensuel (50 M RU) en ~3 semaines.
#    QUEUE_WORKER=off (variable Render) désactive complètement le worker.
CMD php artisan config:cache && \
    php artisan route:cache && \
    (php artisan migrate --force || echo "⚠️ migrate --force a échoué (base injoignable ?) — démarrage quand même.") && \
    (if [ "${QUEUE_WORKER:-on}" != "off" ]; then \
        while true; do php artisan queue:work --stop-when-empty --tries=3 --timeout=90; sleep 60; done; \
     else echo "QUEUE_WORKER=off : worker d'e-mails désactivé."; fi &) && \
    apache2-foreground