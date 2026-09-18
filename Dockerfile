FROM php:8.3-apache

# 1. ዚፕ እና ዩንዚፕ (unzip) እንዲጫኑ ማድረግ
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Composer ማምጣት
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# የ PHP ኤክስቴንሽኖች (Extensions)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Apache rewrite module መክፈት
RUN a2enmod rewrite

# የፕሮጀክት ፋይሎችን መቅዳት
COPY . /var/www/html/

# Composer dependencies መጫን
RUN composer install --no-dev --optimize-autoloader

# የባለቤትነት መብት ማስተካከል (Permissions)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
