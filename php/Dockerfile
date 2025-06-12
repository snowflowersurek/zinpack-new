FROM php:8.3-fpm

# 필요한 PHP 확장 프로그램 설치
RUN apt-get update && apt-get install -y \
    libjpeg-dev \
    libpng-dev \
    && docker-php-ext-install gd mysqli exif

# 사용자 정의 php.ini 설정 복사
COPY conf.d/custom.ini /usr/local/etc/php/conf.d/
