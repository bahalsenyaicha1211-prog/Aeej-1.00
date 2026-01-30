FROM php:8.2-apache

# 1. Installation des dépendances système + Node.js 20
RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libicu-dev curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql zip bcmath intl

# 2. Configuration Apache
RUN a2enmod rewrite
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

# 6. Nettoyage du cache, Migrations, Lancement du Worker (Emails) et Apache
# La commande entre parenthèses avec le '&' permet d'envoyer les emails en tâche de fond
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan route:clear && \
    php artisan migrate --force && \
    (php artisan queue:work --tries=3 --timeout=90 &) && \
    apache2-foreground