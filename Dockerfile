FROM php:8.2-apache

# Install ekstensi MySQL PDO
RUN docker-php-ext-install pdo_mysql mysqli

# Arahkan web server ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Copy semua file projek ke dalam server
COPY . /var/www/html

# Berikan izin akses folder uploads
RUN chmod -R 777 /var/www/html/app/Views/Mahasiswa/uploads