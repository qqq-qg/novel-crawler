php artisan queue:listen
php artisan queue:listen --queue=Content
php artisan task:run

脚本执行监听队列
nonup /usr/local/php/bin/php /var/www/mars/artisan queue:listen --queue=Content >/dev/null 2>&1 &

定时任务执行脚本
*/1 * * * * cd /www/eyon-erp && /usr/local/php/bin/php artisan queue:listen >/dev/null 2>&1




