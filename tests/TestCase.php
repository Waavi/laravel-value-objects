<?php

namespace Waavi\ValueObjects\Test;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();
        //$this->app['cache']->clear();
        $this->setUpDatabase($this->app);
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('app.key', 'sF5r4kJy5HEcOEx3NWxUcYj1zLZLHxuu');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $this->artisan('migrate', ['--path' => realpath(__DIR__ . '/../database/migrations')]);

        $app['db']->connection()->getSchemaBuilder()->create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_id')->nullable();
            $table->string('name');
            $table->text('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamp('post_id')->nullable();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('owner_id');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('text')->nullable();
            $table->integer('author_id')->nullable();
            $table->integer('blog_id')->nullable();
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text')->nullable();
            $table->timestamp('date');
            $table->timestamp('post_id')->nullable();
            //$table->timestamp('author');
            $table->timestamps();
        });
    }
}
