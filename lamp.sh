#!/bin/bash

apt update

apt install wget -q -y

# apache
apt install apache2 -y
a2enmod rewrite
a2enmod headers

adduser $USER www-data

service apache2ctl restart

# php

apt install -y php libapache2-mod-php php-mongodb
service apache2ctl restart

# composer 
wget https://getcomposer.org/download/latest-stable/composer.phar
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
apt instal -y php-zip

# symfony requirements
apt install acl -y

