# Social Graph

Dependencies
* PHP >= 5.4
* Laravel 5.0
* PHPUnit
* Mcrypt PHP Extension
* OpenSSL PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension

## Installation

    git clone git@github.com:ilestis/social-graph.git .
    
## Config, config, config

First, create a new empty database for the application. Recommended is a MySQL DB to avoid extra tweeking.

Next, create a ```.env``` file in your application root with the following content:

    DB_HOST=localhost
    DB_DATABASE=your_db
    DB_USERNAME=your_user
    DB_PASSWORD=your_pwd
    
## Composer
Time to tell composer to install everything locally (get yourself a cup of coffee, this can take a while)

    composer install
    
## Database
Create the tables by running the following command in your application root.

    php artisan migrate
    
If the command warns you about being in production, don't worry, this is intended!
    
Now that your db is nicely setup, it's time to import some data into it. The following script will parse the json file located in public/data.json, so feel free to change it if needed.

    php artisan user:import
    
And voilà, your DB and application is ready to roll. Create your vhosts and whatnots, and test out the API.

## API
The API has the following 4 routes.

### /api/user/{userId}
Granted you provided a valid userId, the api will provide you with information regarding the user.
ex: /api/user/1

### /api/user/{userId}/friends
Will output the friends of the provided user.
ex: /api/user/1/friends

### /api/user/{userId}/friends/indirect
Will output the indirect friends (or friends of friends) of the provided user.
ex: /api/user/1/friends/indirect

### /api/user/{userId}/friends/suggest
Will output the suggested friends of the user.
ex: /api/user/5/friends/suggest

## Tests

All the Unit tests written with PHPUnit and are located in the *tests/* folder. 
To launch all the tests, simply execute the following command in your termainal:

    phpunit