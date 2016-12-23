# btc_system
btcclub

0 0 * * * /usr/bin/php /var/www/html/dtcclub/scripts/jobs/cron.php
* * * * * /usr/bin/php /var/www/html/dtcclub/scripts/jobs/check_service.php

netstat -tulapn|grep ip:3000