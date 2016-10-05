FROM php:7.0-fpm

RUN wget https://www.dotdeb.org/dotdeb.gpg && \
    apt-key add dotdeb.gpg && \
    apt-key adv --keyserver ha.pool.sks-keyservers.net --recv-keys A4A9406876FCBD3C456770C88C718D3B5072E1F5 && \
    cat > /etc/apt/sources.list.d/mysql.list "deb http://repo.mysql.com/apt/debian/ jessie mysql-5.7" && \
    apt-get -y install mysql-client && \
    docker-php-ext-install -j$(nproc) mysql
