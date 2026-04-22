FROM php:8.2-apache

# Install ekstensi MySQL PDO
RUN docker-php-ext-install pdo_mysql mysqli

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Copy semua file projek ke dalam server
COPY . /var/www/html

# Berikan izin akses folder uploads
RUN chmod -R 777 /var/www/html/app/Views/Mahasiswa/uploads