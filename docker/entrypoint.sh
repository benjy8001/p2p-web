#!/usr/bin/env sh

# Recupération de l'utilisateur et groupe courant
uid=$(stat -c %u /var/www/html)
gid=$(stat -c %g /var/www/html)
user='foo'

# fonction pour charger les variables d'environnement du fichier .env
function dotenv()
{
    echo "dotenv"
    set -a
    if [ -f .env ]
    then
        source .env
    fi
    set +a
}

# Ajout d'une fonction a executer au démarrage de la VM, qui peut mettre à jour la BDD
# et les assets si spécifié dans la variable d'environnement.
function beforeStart()
{
    if [ "enabled" == "$APP_AUTO_UPDATE" -a -d vendor  ]; then
        echo "beforeStart actions"
    fi
}

function fixDirectoryPermissions()
{
    echo "fixDirectoryPermissions"
    chown $user -R /var/www/html
    for DIRECTORY in '/var/www/html/bootstrap/cache' '/var/www/html/storage'
    do
            if [ -d  "$DIRECTORY"  ]
            then
                chmod -R 777 "$DIRECTORY"
            fi
    done
}

dotenv

# Si on a activé 'utilisation de XDEBUG on le configure pour PHP
if [ "enabled" == "$APP_XDEBUG" ]; then
    # Enable xdebug
    echo 'Xdebug  is enabled'
else
    # Disable xdebug
    if [ -e /usr/local/etc/php/conf.d/xdebug.ini ]; then
        rm -f /usr/local/etc/php/conf.d/xdebug.ini
    fi
    echo 'Xdebug  is disabled'
fi

if [ ! -z $QUEUE_NAME ]; then
    php artisan queue:work --queue=$QUEUE_NAME
fi

if [ $uid == 0 ] && [ $gid == 0 ]; then
    if [ $# -eq 0 ]; then
        beforeStart
    	  fixDirectoryPermissions
        php-fpm --allow-to-run-as-root
    else
        echo "$@"
        exec "$@"
    fi
fi

echo 'Environnement is :'$APP_MODE
# don't use \d with sed, it is not digits but the special char like \r\n :) or use perl but perl is not present everywhere.
sed -i -r "s/$user:x:[[:digit:]]+:[[:digit:]]+:/$user:x:$uid:$gid:/g" /etc/passwd
sed -i -r "s/bar:x:[[:digit:]]+:/bar:x:$gid:/g" /etc/group

sed -i "s/user = www-data/user = $user/g" /usr/local/etc/php-fpm.d/www.conf
sed -i "s/group = www-data/group = bar/g" /usr/local/etc/php-fpm.d/www.conf

if [ $# -eq 0 ]; then
	beforeStart
	fixDirectoryPermissions
  php-fpm
else
  echo gosu $user "$@"
  exec gosu $user "$@"
fi