#!/bin/sh
set -eu

mysql_args="-h${MYSQLHOST} -P${MYSQLPORT:-3306} -u${MYSQLUSER} -p${MYSQLPASSWORD} ${MYSQLDATABASE}"

if mysql ${mysql_args} -e 'SELECT 1 FROM `user` LIMIT 1' >/dev/null 2>&1; then
    echo 'MySQL schema already exists; skipping import.'
    exit 0
fi

mysql ${mysql_args} < /var/www/html/database/gameina.sql
