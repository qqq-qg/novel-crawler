php artisan queue:work --queue=default --memory=512
php artisan queue:work --queue=Content --memory=512
php artisan queue:work --queue=Search

php artisan task:run

脚本执行监听队列
nonup /usr/local/php/bin/php /var/www/mars/artisan queue:listen --queue=default,Content --sleep=3 >/dev/null 2>&1 &
php artisan queue:listen --queue=default,Content --sleep=3

定时任务执行脚本
*/1 * * * * cd /www/eyon-erp && /usr/local/php/bin/php artisan queue:listen >/dev/null 2>&1




