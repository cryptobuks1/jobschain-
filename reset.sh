echo '----------------------------------------'
echo -e 'RESETING LARAVEL FOLDER PERMISSIONS:'
echo '----------------------------------------'
echo '- change owner to www-data:www-data -'
sudo chown -R www-data:www-data ./
echo '- chmod files to 664 -'
sudo find ./ -type f -exec chmod 664 {} \; 
echo '- chmod directories to 755 -'
sudo find ./ -type d -exec chmod 755 {} \;
echo '- change group to www-data -'
sudo chgrp -R www-data ./storage ./bootstrap/cache
echo '- change permission of storage and cache-'
sudo chmod -R ug+rwx ./storage ./bootstrap/cache
sudo chown -R $USER ./node_modules
