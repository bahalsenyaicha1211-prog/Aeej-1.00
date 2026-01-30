FROM php:8.2-apache

# Installer extensions PHP nécessaires à Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier le projet
COPY . /var/www/html

# Définir le dossier public comme racine Apache
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Donner les permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
