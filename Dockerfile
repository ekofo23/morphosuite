FROM php:8.1-apache

# 1. Installer les dépendances système et les drivers PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# 2. Activer le module rewrite d'Apache
RUN a2enmod rewrite

# 3. Copier les fichiers du projet
COPY . /var/www/html

# 4. Définir le dossier public comme DocumentRoot Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# 5. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Exécuter composer install sans lancer les scripts auto-discover pendant le build de l'image
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 7. Configurer les permissions storage et cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80