curl -o box.phar -LSs https://github.com/box-project/box2/releases/download/2.7.5/box-2.7.5.phar
php -d phar.readonly=0 box.phar build