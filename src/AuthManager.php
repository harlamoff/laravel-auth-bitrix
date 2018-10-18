<?php

namespace AndreyVasin\LaravelAuthBitrix;

/**
 * Class AuthManager
 *
 * @package App\CoreExtensions\AuthBitrix
 */
class AuthManager extends \Illuminate\Auth\AuthManager
{
    /**
     * Create a session based authentication guard.
     *
     * @param  string  $name
     * @param  array  $config
     * @return \Illuminate\Auth\SessionGuard
     */
    public function createSessionDriver($name, $config)
    {
        $provider = $this->createUserProvider($config['provider'] ?? null);

        // Запускаем стандартную сессию для

        $guard = new SessionGuard($name, $provider, $this->app['session.store']); // Переопределяется стандартный

        // When using the remember me functionality of the authentication services we
        // will need to be set the encryption instance of the guard, which allows
        // secure, encrypted cookie values to get generated for those cookies.
        if (method_exists($guard, 'setCookieJar')) {
            $guard->setCookieJar($this->app['cookie']);
        }

        if (method_exists($guard, 'setDispatcher')) {
            $guard->setDispatcher($this->app['events']);
        }

        if (method_exists($guard, 'setRequest')) {
            $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
        }

        return $guard;
    }
}