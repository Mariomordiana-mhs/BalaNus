# Menggunakan image resmi PHP 8.3 dengan Apache
FROM php:8.3-apache

# Install ekstensi yang diwajibkan oleh CodeIgniter 4 (intl, mysqli, zip)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl mysqli pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Mengaktifkan mode rewrite Apache (wajib agar routing CI4 tidak 404)
RUN a2enmod rewrite

# Mengubah root folder Apache agar mengarah langsung ke folder /public CI4
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Salin seluruh file proyek CodeIgniter ke dalam container
COPY . /var/www/html

# Berikan hak akses penuh ke folder 'writable' agar CI4 bisa menyimpan session/cache
RUN chown -R www-data:www-data /var/www/html/writable
RUN chmod -R 777 /var/www/html/writable

EXPOSE 80