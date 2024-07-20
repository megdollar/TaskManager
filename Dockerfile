FROM php:7.4-apache
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www/html/
WORKDIR /var/www/html/
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/views|g' /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory "/var/www/html/css">\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory "/var/www/html/views">\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory "/var/www/html/php">\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf
EXPOSE 80
