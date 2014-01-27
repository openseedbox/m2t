## M2T

Openseedbox Magnet to Torrent Converter

## Overview

This is an API that allows a magnet link, torrent URL, torrent file or torrent info_hash to be posted to it. It then returns a list of the files, seeds, peers, downloads etc of the resulting torrent file.

It gets this data by loading the torrent into an implemented backend. Currently the only implemented backend is transmission-daemon, but it requires [this patch](https://trac.transmissionbt.com/ticket/5547) to work.

## Requirements
- PHP 5.4+
- MySQL 5.5+
- transmission-daemon 2.80+ with [this patch](https://trac.transmissionbt.com/ticket/5547) applied
- Beanstalkd
- Vagrant unless you want to set up everything yourself

## Quick Install

1. Clone the repo and run `vagrant up`
1. Go to http://localhost:8081
1. Optionally configure your webserver to proxy requests for a certain domain to localhost:8081
1. ???
1. Profit!

## Slower Install
1. Clone the repo
1. Run (or examine) the ./vagrant/provision.sh script. This script does the following:
	- Installs and configures Apache/PHP/MySQL/Beanstalkd/transmission-daemon
	- Installs upstart scripts for m2t and sets them to start on startup
	- Starts the services
   Note that this script is specific to an Ubuntu environment.
1. Edit the app/config/database.php and app/config/queue.php to make sure your settings are correct
1. Run `php artisan db:migrate`
1. Browse to the site. You did configure your webserver correctly, right?



