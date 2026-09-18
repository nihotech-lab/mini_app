FROM php:8.3-apache

# 1. Shigo da Composer daga hukumar sa ta asali
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 2. Shigar da abubuwan da PHP ke bukata (PDO da MySQLi)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 3. Kunna Apache rewrite module
RUN a2enmod rewrite

# 4. Kwafi duk fayilolin aikin ka zuwa cikin gidan yanar gizo na container kafin a gudanar da composer
COPY . /var/www/html/

# 5. Shigar da ɗakunan karatu (packages) ta hanyar Composer
RUN composer install --no-dev --optimize-autoloader

# 6. Saita izinin mallaka na fayilolin ga mai amفani da apache (www-data)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# FROM php:8.3-apache

# RUN composer install

# RUN docker-php-ext-install pdo pdo_mysql mysqli

# RUN a2enmod rewrite

# COPY . /var/www/html/

# RUN chown -R www-data:www-data /var/www/html

# EXPOSE 80

# CMD ["apache2-foreground"]




