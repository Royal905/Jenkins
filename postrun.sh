#!/bin/bash

echo "Installing havells code"
# cd to below if code is placed in /var/www/html
#cd /var/www/html/

cd /var/www/html/havells/

php -d memory_limit=-1 bin/magento s:up
php -d memory_limit=-1 bin/magento s:d:c
php -d memory_limit=-1 bin/magento s:s:d -f
php -d memory_limit=-1 bin/magento c:f
php -d memory_limit=-1 bin/magento indexer:reindex
#php -d memory_limit=-1 bin/magento cron:run
chmod 777 -R var/ generated/ pub/
echo "Havells code deployment finished, ready to access"

