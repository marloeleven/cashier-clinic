<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use App\User;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    // protected $policies = [
    //     Post::class => PostPolicy::class,
    // ];


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        // $this->registerPolicies();

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('token')) {
                $user = User::select(['id', 'username', 'type', 'token_expiration'])->where('token', $request->header('token'))->first();

                if ($user) {

                    $now = Carbon::now()->toDateTimeString();
                    $token = new Carbon($user->token_expiration);

                    if ($token->gt($now)) {
                        return $user;
                    }

                    return null;
                
                }
            }
        });
    }
}
