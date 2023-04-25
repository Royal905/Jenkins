FROM ubuntu:18.04
# Install Apache

RUN apt update -y
RUN apt install apache2 apache2-utils nano git -y
RUN apt install curl -y
RUN apt update -y

# Change Apache Configuration
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN echo "ServerSignature Off" >>  /etc/apache2/apache2.conf
RUN echo "ServerTokens Prod" >>   /etc/apache2/apache2.conf

#Set timezone of the containers as per need
ENV TZ=Asia/Kolkata
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

#Install EPEL Repo
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php  -y
RUN add-apt-repository ppa:ondrej/apache2  -y

#Install PHP & EXTENTIONS
RUN apt update -y
RUN apt install php8.1 -y
RUN apt-get install libapache2-mod-php8.1 php8.1-common php8.1-mbstring  php8.1-soap php8.1-gd php8.1-xml php8.1-intl php8.1-mysql php8.1-cli php8.1-mcrypt php8.1-zip php8.1-curl  php8.1-bcmath php8.1-xmlrpc php8.1-gmp -y

#Install composer
RUN cd /home
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

#Enable Rewrite and Header mod
RUN a2enmod rewrite
RUN a2enmod headers

#Configure Php.ini
RUN sed -E -i -e 's/memory_limit = 128M/memory_limit = -1/' /etc/php/8.1/cli/php.ini
RUN sed -E -i -e 's/post_max_size = 8M/post_max_size = 512M/' /etc/php/8.1/cli/php.ini
RUN sed -E -i -e 's/upload_max_filesize = 2M/upload_max_filesize = 64M/' /etc/php/8.1/cli/php.ini
RUN sed -E -i -e 's/;max_input_vars = 1000/max_input_vars = 1000000/' /etc/php/8.1/cli/php.ini
RUN sed -E -i -e 's/expose_php = On/expose_php = Off/' /etc/php/8.1/cli/php.ini

#COPY required files
COPY run.sh /scripts/run.sh
COPY postrun.sh /scripts/postrun.sh
COPY headless /var/www/html/headless/
EXPOSE 80
USER root

#RUN start script000
ENTRYPOINT ["sh", "/scripts/run.sh" ]
