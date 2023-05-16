#!/bin/bash
# Install supervisor.
apt-get install -y supervisor

# Add the config directory if it does not exist.
if [ ! -d "/etc/supervisor/conf.d" ];
then
    mkdir -p "/etc/supervisor/conf.d"
fi

# Copy the config file.
cp /var/www/html/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Refresh process using configuration file.
supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*

# Initialize scheduled Laravel commands.
service cron stop
apt-get update -y
apt-get install -y cron
COMMAND="* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1"
crontab -l | grep "$COMMAND" 1>/dev/null 2>&1
(( $? == 0 )) && exit
crontab -l >/tmp/crontab.tmp
echo "$COMMAND" >> /tmp/crontab.tmp
crontab /tmp/crontab.tmp
rm /tmp/crontab.tmp
service cron start