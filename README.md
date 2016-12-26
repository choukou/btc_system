# btc_system
btcclub

0 0 * * * /usr/bin/php /var/www/html/dtcclub/scripts/jobs/cron.php
* * * * * /usr/bin/php /var/www/html/dtcclub/scripts/jobs/check_service.php

netstat -tulapn|grep ip:3000
===
yum install gcc-c++ make
sudo yum install openssl-devel
curl --silent --location https://rpm.nodesource.com/setup_7.x | sudo -E bash -
yum -y install nodejs
git clone https://github.com/isaacs/npm.git
cd npm
sudo make install