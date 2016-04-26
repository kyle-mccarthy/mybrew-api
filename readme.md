# mybeer-api

## Deployment Instructions
1. Set up Server or [Vagrant/Homestead Box](https://laravel.com/docs/5.2/homestead)
2. Clone the repo and setup the virtual hosts to the public directory of the project
3. Run `composer install` to install the dependencies and setup the vendor folder
4. Setup the [database configuration](https://laravel.com/docs/5.2/database)
5. Once the database is set up, run the migrations `php artisan migrate`
6. Visit the URL or IP to ensure that the application and server and handling requests correctly
