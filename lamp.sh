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

apt install -y php php-curl php-xml php-mbstring php-intl php-zip libapache2-mod-php php-mysql php-mongodb 
service apache2ctl restart

# composer 
wget https://getcomposer.org/download/latest-stable/composer.phar
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer


# symfony requirements
apt install acl -y

