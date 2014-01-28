#!/bin/bash

echo "Provisioning M2T VM..."

echo "Updating APT repo and packages"
#apt-get -qqy update
#DEBIAN_FRONTEND=noninteractive apt-get -qqyf upgrade

echo "Installing python-software-properties"
#apt-get -qqy install python-software-properties

echo "Adding PHP PPA"
#apt-add-repository "ppa:ondrej/php5"
#apt-get -qqy update

echo "Installing Apache"
#apt-get -qqy install apache2

echo "Installing PHP"
#apt-get -qqy install php5 php5-mcrypt php5-curl php5-mysqlnd 2>&1

echo "Installing MySQL"
#apt-get -qqy install mysql-server 2>&1

echo "Installing transmission-daemon"
#apt-get -qqy install build-essential subversion wget autoconf libtool pkg-config libcurl4-openssl-dev libevent-dev gettext intltool
#cd /home/vagrant
#mkdir -p src
#cd src
#svn co svn://svn.transmissionbt.com/Transmission/tags/2.82 transmission
#cd transmission
#wget -O - "https://trac.transmissionbt.com/raw-attachment/ticket/5547/svndiff.diff" 2>/dev/null | patch -p0 "libtransmission/rpcimpl.c"
#touch po/Makefile.in.in
#./autogen.sh --disable-nls 2>&1
#make 2>&1
#sudo make install 2>&1

echo "Configuring transmission-daemon"
#mkdir -p /etc/transmission-daemon
#chown -R vagrant /etc/transmission-daemon
#cp /vagrant/transmission-daemon-settings.json /etc/transmission-daemon/settings.json
#sudo cp /vagrant/transmission-daemon.conf /etc/init
#initctl stop transmission-daemon 2>&1
#initctl start transmission-daemon

echo "Installing git"
apt-get -qqy install git 2>&1

echo "Setting up m2t"
a2dissite 000-default
mkdir -p /var/www/m2t
mkdir -p /var/www/m2t-interface
cd /var/www/m2t

echo "Cloning m2t api"
git clone https://github.com/openseedbox/m2t.git . 2>&1

echo "Installing composer"
php -r "eval('?>'.file_get_contents('https://getcomposer.org/installer'));"
mv composer.phar /usr/bin/composer

echo "Configuring m2t api"
composer --no-dev install

echo "Configuring mysql"
echo "create database if not exists m2t; GRANT ALL PRIVILEGES ON *.* TO 'openseedbox'@'localhost' IDENTIFIED BY 'password' WITH GRANT OPTION; FLUSH PRIVILEGES;" | mysql -u root
php artisan migrate

echo "Cloning m2t web interface"
cd /var/www/m2t-interface
git clone https://github.com/openseedbox/m2t.git . 2>&1
git checkout gh-pages 2>&1
cp /vagrant/api_location.js js/api_location.js
chown -R www-data /var/www
chgrp -R www-data /var/www

echo "Copying up apache config"
cp /vagrant/m2t.conf /etc/apache2/sites-available
cp /vagrant/m2t-interface.conf /etc/apache2/sites-available
a2ensite m2t
a2ensite m2t-interface
a2enmod rewrite

echo "Restarting apache"
service apache2 reload