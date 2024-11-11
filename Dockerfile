FROM ubuntu:24.04

ENTRYPOINT ["usr/sbin/apache2ctl", "-D", "FOREGROUND"]

RUN apt update && apt dist-upgrade -y

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

COPY ./app.conf /tmp/app.conf

RUN apt install -q -y wget unzip

# apache
RUN apt install apache2 -q -y
RUN a2enmod rewrite
RUN a2enmod headers

RUN cp /tmp/app.conf /etc/apache2/sites-available/app.conf
RUN a2dissite 000-default.conf
RUN rm -rf /var/www/html
RUN a2ensite app.conf

# php
RUN apt install -q -y php libapache2-mod-php php-mongodb php-zip php-redis

# composer
RUN wget https://getcomposer.org/download/latest-stable/composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

WORKDIR /var/www/app

