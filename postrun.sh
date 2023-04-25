#!/bin/bash

echo "Installing havells code"

cd /var/www/html/headless/

composer install

php -d memory_limit=-1 bin/magento module:enable --all
php -d memory_limit=-1 bin/magento setup:upgrade
php -d memory_limit=-1 bin/magento s:d:c
php -d memory_limit=-1 bin/magento s:s:d -f
php -d memory_limit=-1 bin/magento c:f
php -d memory_limit=-1 bin/magento indexer:reindex
#php -d memory_limit=-1 bin/magento cron:run
chmod 777 -R var/ generated/ pub/
echo "Havells code deployment finished, ready to access"
