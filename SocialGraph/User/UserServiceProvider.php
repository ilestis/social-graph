<?php namespace SocialGraph\User;

use Illuminate\Support\ServiceProvider;
use Route, View, App, DB;

class UserServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Enable the Query Log to check what queries happened
        DB::enableQueryLog();
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->addRoutes();
    }

    /**
     * Add the routes to the application
     */
    protected function addRoutes()
    {
        Route::model('user', 'SocialGraph\User\Models\User');


        // API routes
        Route::group(array('prefix' => 'api'), function () {

            Route::get('/user/{user}', [
                'as' => 'api.user',
                'uses' => 'App\Http\Controllers\Api\User\UserApiController@show'
            ]);

            // Friends of the user
            Route::get('/user/{user}/friends', [
                'as'     => 'api.user.friends',
                'uses' => 'App\Http\Controllers\Api\User\FriendApiController@friends'
            ]);

            // Friends of Friends
            Route::get('/user/{user}/friends/indirect', [
                'as'     => 'api.user.friends.indirect',
                'uses' => 'App\Http\Controllers\Api\User\FriendApiController@indirect'
            ]);

            // Suggested Friends
            Route::get('/user/{user}/friends/suggest', [
                'as'     => 'api.user.friends.suggest',
                'uses' => 'App\Http\Controllers\Api\User\FriendApiController@suggest'
            ]);
        });
    }

    protected function registerCommands()
    {
        $this->app->singleton('user.command.import', 'SocialGraph\User\Importers\UserDataImporter');
        $this->commands('user.command.import');
    }
}