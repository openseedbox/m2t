## M2T

Openseedbox Magnet to Torrent Converter. **Note this isnt working yet**, im just putting it here as a backup of my code.

## Overview

This is an API that allows a magnet link, torrent URL, torrent file or torrent info_hash to be posted to it. It then returns a list of the files, seeds, peers, downloads etc of the resulting torrent file.

It gets this data by loading the torrent into an implemented backend. Currently the only implemented backend is transmission-daemon, but it requires [this patch](https://trac.transmissionbt.com/ticket/5547) to work.

## Requirements
- PHP 5.4+
- MySQL 5.5+
- transmission-daemon 2.80+ with [this patch](https://trac.transmissionbt.com/ticket/5547) applied

