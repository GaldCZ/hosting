#!/bin/bash

#user=$(cat bitnami_credentials | awk -F "'" '{print $4}')
#password=$(cat bitnami_credentials | awk -F "'" '{print $2}')

#awk 'NR==14{print "<p>username: $user, password: $password</p>"\}1' /opt/bitnami/apps/wordpress/htdocs/readme.html

######

ID=$(curl http://169.254.169.254/latest/meta-data/instance-id)
USER=$(cat bitnami_credentials | grep user | awk -F "'" '{print $2}')
PASS=$(cat bitnami_credentials | grep user | awk -F "'" '{print $4}')

mysql -hdatabaseyxxxxx -P 3307 -u xxx -pxxxx@ -e "INSERT INTO wp_logins.first_logins (username, password, instance_id) VALUES('"$USER"', '"$PASS"', '"$ID"');"
