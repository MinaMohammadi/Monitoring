
-------------------------------------------------------------------------
#!/bin/bash

CONFIG_PATH=/root/.s3cfg
MOUNT_POINT=/opt/db-backup/
BUCKET_NAME=prj1-db-backup

echo "$(date +"%Y-%m-%d__%H-%M-%S") ... Start Removing Old Files From $MOUNT_POINT" >> /var/log/sync_storage.log

find $MOUNT_POINT -type f -mtime +5 -delete

echo "$(date +"%Y-%m-%d__%H-%M-%S") ... End Removing Old Files From $MOUNT_POINT" >> /var/log/sync_storage.log

echo "$(date +"%Y-%m-%d__%H-%M-%S") ... Start Syncing With S3" >> /var/log/sync_storage.log

s3cmd -c $CONFIG_PATH sync $MOUNT_POINT --preserve --delete-removed s3://$BUCKET_NAME

RESULT=$?
if [ $RESULT -eq 0 ]; then
    echo "$(date +"%Y-%m-%d__%H-%M-%S") ... Success Syncing With S3" >> /var/log/sync_storage.log
else
    echo "$(date +"%Y-%m-%d__%H-%M-%S") ... ERROR Syncing With S3" >> /var/log/sync_storage.log
fi

echo "$(date +"%Y-%m-%d__%H-%M-%S") ... End Syncing With S3" >> /var/log/sync_storage.log


-----------------------------------------------------

log path---->

/var/log/sync_storage.log

