<?php

namespace Bitfumes\ApiAuth\Tests;

use Bitfumes\ApiAuth\ApiAuthServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class TestCase extends BaseTestCase
{
    public function setup() : void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        app()->register(LaravelServiceProvider::class); // register tymon package
        app()->register(ImageServiceProvider::class); // register tymon package
        $this->loadMigrations();
        $this->loadFactories();
    }

    protected function loadFactories()
    {
        $this->withFactories(__DIR__ . '/../src/database/factories'); // package factories
        $this->withFactories(__DIR__ . '/database/factories'); // Test factories
    }

    protected function loadMigrations()
    {
        $this->loadLaravelMigrations(['--database' => 'testing']); // package migrations
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations'); // test migrations
        $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('api-auth.models.user', User::class);
        $app['config']->set('jwt.secret', 'abcdef');

        $app['config']->set('auth.guards.api.driver', 'jwt');
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [ApiAuthServiceProvider::class];
    }

    public function createUser($args = [], $num=null)
    {
        return factory(User::class, $num)->create($args);
    }

    public function authUser()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        return $user;
    }
}
