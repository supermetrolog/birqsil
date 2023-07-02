#!/bin/sh

# Setup
apt install
apt update

# nginx
apt install nginx

echo "# All requests with response
      # codes 2xx and 3xx will not be logged:
      map $status $loggable {
              ~^[23]  0;
              default 1;
          }" > /etc/nginx/conf.d/default.conf

echo "server {
          server_name api.birqsil.com;

          root /home/server/birqsil/backend/web;
          index index.php;

          location / {
              try_files $uri $uri/ /index.php?$query_string;
          }

          error_log /var/log/nginx/app.error.log;
          access_log /var/log/nginx/app.access.log combined if=$loggable;

          location ~ \.php$ {
                  try_files $uri =404;
                  fastcgi_split_path_info ^(.+\.php)(/.+)$;
                  fastcgi_pass unix:/run/php/php8.2-fpm.sock;
                  fastcgi_index index.php;
                  include fastcgi_params;
                  fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
                  fastcgi_param PATH_INFO $fastcgi_script_name;
                  fastcgi_read_timeout 1200;
              }
      }
" > /etc/nginx/conf.d/api.birqsil.com.conf


# php-fpm
apt-get install software-properties-common
add-apt-repository -y ppa:ondrej/php
apt update
apt install php8.2-fpm

cp /home/server/birqsil/docker/php/conf/php.ini /etc/php/8.2/fpm/php.ini
# OS php dependencies

#apt install icu-libs \
#  libintl \
#  build-base \
#  zlib-dev \
#  cyrus-sasl-dev \
#  libgsasl-dev \
#  oniguruma-dev \
#  procps \
#  imagemagick \
#  patch \
#  bash \
#  htop \
#  acl \
#  apk-cron \
#  augeas-dev \
#  autoconf \
#  curl \
#  ca-certificates \
#  dialog \
#  freetype-dev \
#  gomplate \
#  git \
#  gcc \
#  gettext-dev \
#  icu-dev \
#  libcurl \
#  libffi-dev \
#  libgcrypt-dev \
#  libjpeg-turbo-dev \
#  libpng-dev \
#  libmcrypt-dev \
#  libressl-dev \
#  libxslt-dev \
#  libzip-dev \
#  linux-headers \
#  libxml2-dev \
#  ldb-dev \
#  make \
#  musl-dev \
#  mysql-client \
#  openssh-client \
#  pcre-dev \
#  ssmtp \
#  supervisor \
#  su-exec \
#  wget

# php extensions
apt install php8.2-common php8.2-zip php8.2-curl php8.2-xml php8.2-xmlrpc php8.2-mysql php8.2-pdo php8.2-gd php8.2-imagick php8.2-ldap php8.2-imap php8.2-mbstring php8.2-intl php8.2-cli php8.2-tidy php8.2-bcmath php8.2-opcache

# Composer
apt install composer

# MYSQL
apt install mysql-server

mysql -uroot -proot
  CREATE DATABASE birqsil;
  CREATE USER 'birqsil'@'localhost' IDENTIFIED BY 'password';
  GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD on *.* TO 'birqsil'@'localhost' WITH GRANT OPTION;
  FLUSH PRIVILEGES;
# RESTARTING

systemctl restart mysql
systemctl restart php8.2-fpm
systemctl restart nginx

# Create server user
useradd -m server
ssh-keygen

# Clone repository
cd /home/server
git clone git@github.com:supermetrolog/birqsil.git

# Install project dependencies
composer install

# Configure env
chmod 770 -R /home/server
chown server:www-data -R /home/server

cp /home/server/birqsil/common/config/local_example.php /home/server/birqsil/common/config/local.php
# => CHANGE CONFIG