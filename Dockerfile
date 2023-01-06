FROM ubuntu:20.04

ENTRYPOINT ["usr/sbin/apache2ctl", "-D", "FOREGROUND"]

RUN apt update && apt dist-upgrade

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections

COPY ./lamp.sh lamp.sh

RUN ./lamp.sh


