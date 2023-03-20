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
