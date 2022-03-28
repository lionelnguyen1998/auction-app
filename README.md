1. clone code tại auction-admin và auction-app về
2. Đặt vào cùng thư mục
( Tại auction-admin chạy docker-compose up -d  
=> Run docker exec -it auction-admin sh 
=> composer install 
=> php artisan migrate)
3. Tại auction-app chạy docker exec -it auction-app sh 
4. run composer install
5. git fetch origin
6. git checkout origin/app
7. git checkout -b newbranch để code các chức năng khác.
