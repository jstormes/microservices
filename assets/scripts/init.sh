#!/usr/bin/env bash
#
# This is a Docker Anti-Pattern.  I am putting all the services into
# one container and I am doing it knowing that this is NOT the way
# you should do it.   That is just how I roll...
#
# Breaking the law, breaking the law ...
#
# This is intended for development. It also allows less experienced
# system operators to deploy to system like QNAP NAS server as one
# container, without having to understand how to connect and
# maintain separate services.
#

if [ $# -eq 0 ]; then
    echo "No command passed, redirecting logs to stdout and stderr"
    ln -nsf /dev/stdout /var/log/apache2/access.log
    ln -nsf /dev/stderr /var/log/apache2/error.log
    ln -nsf /dev/stderr /var/log/apache2/other_vhosts_access.log
    ln -nsf /dev/stdout /var/log/mysql.log
    ln -nsf /dev/stderr /var/log/mysql.err
#    ln -nsf /dev/stdout /var/log/cron.log
fi


echo "starting Syslog NG"
service  syslog-ng start

echo "Starting MariaDb"
mysqld_safe &
MYSQL_PID=$!

echo "Starting Apache"
apachectl -DFOREGROUND &
APACHE_PID=$!

echo "Starting Cron"
/usr/sbin/cron -f &
CRON_PID=$!

echo "Starting at"
/usr/sbin/atd &
AT_PID=$!

echo "Starting Redis"
/usr/bin/redis-server &
REDIS_PID=$!

echo "Starting OpenLDAP"
service slapd start


# A Hack to set the root password for MariaDb/MySQL
echo "Checking for MariaDb password"
if [ -z "${MY_ROOT_PW}" ]; then
    echo "No MariaDb root password passed, you should pass a new password via the MY_ROOT_PW variable";
else
    echo "Setting MySQL Password";
    sleep 2
    echo "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('$MY_ROOT_PW');" | mysql -pnaked
    unset MY_ROOT_PW
fi

echo "**************************************************"
echo "* LAMP Server is ready "
echo "* Server IP address is $(hostname -i) "
echo "**************************************************"

echo "Checking for startup scripts"
run-parts /etc/start
if [ -z "${START_SCRIPT_PATH}" ]; then
    echo "No START_SCRIPT_PATH set.";
else
    echo "START_SCRIPT_PATH set trying to run.";
    run-parts  ${START_SCRIPT_PATH}
fi

if [ $# -ne 0 ]; then
    echo "running ${@}"
    exec "$@"
fi

while /bin/true; do

  if ! ps -p $MYSQL_PID > /dev/null
  then
    echo "MySQL died!!!"
    exit -1
  fi

  if ! ps -p $APACHE_PID > /dev/null
  then
    echo "Apache died!!!"
    exit -1
  fi

  if ! ps -p $CRON_PID > /dev/null
  then
    echo "Cron died!!!"
    exit -1
  fi

# At appears to re-spawn itself under a different PID, I miss `systemd`.
#  if ! ps -p $AT_PID > /dev/null
#  then
#    echo "At died!!!"
#    exit -1
#  fi

  sleep 5
done
