1. clone code auction-admin and auction-app repo
2. save in the same folder
   ( In auction-admin folder: docker-compose up -d  
=> Run:  docker exec -it auction-admin sh 
=> Run: composer install 
=> Run: php artisan migrate)
3. In auction-app folder: Run docker exec -it auction-app sh 
4. Run composer install
