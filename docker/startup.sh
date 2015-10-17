# Please do not manually call this file!
# This script is run by the docker container when it is "run"

# Run the apache process in the background
/usr/sbin/apache2 -D APACHE_PROCESS &

service apache2 restart

# If we had database migrations, they would be called here.
#/usr/bin/php /path/to/migration/manager.php

# Start the cron service in the foreground
cron -f