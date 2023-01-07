#!/bin/bash

apt install -q -y wget unzip

# apache
apt install apache2 -q -y
a2enmod rewrite
a2enmod headers

adduser $USER www-data
cp /tmp/app.conf /etc/apache2/sites-available/app.conf
a2dissite 000-default.conf
rm -rf /var/www/html
a2ensite app.conf

# php
apt install -q -y php libapache2-mod-php php-mongodb php-zip

# composer 
wget https://getcomposer.org/download/latest-stable/composer.phar
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer


