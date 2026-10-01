# Gunakan base image PHP 8.2 dengan Apache
FROM php:8.2-apache

# Update package lists dan install system dependencies (PostgreSQL libs, Zip, dsb)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions yang diwajibkan Laravel & PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache mod_rewrite agar routing Laravel (index.php) bekerja normal
RUN a2enmod rewrite

# Ubah default DocumentRoot Apache agar mengarah langsung ke folder /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set Working Directory
WORKDIR /var/www/html

# Salin Composer Binary dari official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (versi 20 LTS) & NPM untuk memproses Vite/Tailwind
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Salin semua isi project ke dalam container
COPY . /var/www/html/

# Install ekstensi PHP melalui Composer (Mode Produksi & tanpa dependency dev)
RUN composer install --no-dev --optimize-autoloader

# Install ekstensi Frontend & build production assets
RUN npm install && npm run build

# Berikan hak akses penuh ke web-server pengguna apache (www-data) untuk storage & bootstrap
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buat symlink agar public/storage terhubung ke storage/app/public
RUN php artisan storage:link

# Buka akses Port 80 untuk lalu lintas HTTP
EXPOSE 80

# Jalankan Apache saat container distart
CMD ["apache2-foreground"]