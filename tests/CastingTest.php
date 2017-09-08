<?php

namespace Waavi\ValueObjects\Test;

use Carbon\Carbon;
use Waavi\ValueObjects\Test\Models\Account;
use Waavi\ValueObjects\Test\Models\Address;
use Waavi\ValueObjects\Test\Models\Blog;
use Waavi\ValueObjects\Test\Models\Comment;
use Waavi\ValueObjects\Test\Models\Email;
use Waavi\ValueObjects\Test\Models\Person;
use Waavi\ValueObjects\Test\Models\Post;

class CastingTest extends TestCase
{
    /**
     *  @test
     */
    public function it_saves_simple_value_objects()
    {
        $name    = 'example';
        $email   = 'info@waavi.com';
        Account::create([
            'name'  => $name,
            'email' => new Email($email),
        ]);
        $this->assertDatabaseHas('accounts', ['name' => 'example', 'email' => 'info@waavi.com']);
    }

    /**
     *  @test
     */
    public function it_casts_simple_value_objects()
    {
        $name    = 'example';
        $email   = 'info@waavi.com';

        Account::create([
            'name'  => $name,
            'email' => new Email($email),
        ]);

        $account = Account::first();
        $this->assertEquals($name, $account->name);
        // Single field value object:
        $this->assertInstanceOf(Email::class, $account->email);
        $this->assertEquals($email, (string) $account->email);
        $this->assertEquals($email, $account->email->toJson());
    }

    /**
     *  @test
     */
    public function it_saves_complex_value_objects()
    {
        $name    = 'example';
        $address = ['street' => 'Gran vía', 'city' => 'Madrid', 'country' => 'Spain'];
        $account = Account::create([
            'name'    => $name,
            'address' => new Address($address),
        ]);
        $this->assertDatabaseHas('accounts', ['name' => 'example', 'address' => json_encode($address)]);
    }

    /**
     *  @test
     */
    public function it_casts_complex_value_objects()
    {
        $name    = 'example';
        $address = ['street' => 'Gran vía', 'city' => 'Madrid', 'country' => 'Spain'];
        $account = Account::create([
            'name'    => $name,
            'address' => new Address($address),
        ]);

        $account = Account::first();
        $this->assertEquals($name, $account->name);
        // Multiple fields value object:
        $this->assertInstanceOf(Address::class, $account->address);
        $this->assertEquals(json_encode($address), (string) $account->address);
        $this->assertEquals(json_encode($address), $account->address->toJson());
    }

    public function it_cast_value_objects_of_relations()
    {
        $owner = Person::create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => new Email('owner@example.com'),
        ]);

        $this->assertDatabaseHas('people', ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'owner@example.com']);

        $author = Person::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => new Email('author@example.com'),
        ]);

        $this->assertDatabaseHas('people', ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'author@example.com']);

        $blog = new Blog();
        $blog->title = 'My new blog';
        $blog->owner()->associate($owner);
        $blog->save();

        $this->assertDatabaseHas('blogs', ['title' => 'My new blog']);

        $now = Carbon::now();
        $comment = new Comment([
            'text' => 'Lorem Ipsum',
            'date' => $now,
        ]);

        $post = new Post();
        $post->title = 'Hello World';
        $post->text = 'Lorem Ipsum';
        $post->author()->save($author);
        $post->comments()->save($comment);
        $post->blog()->associate($blog);
        $post->save();

        $this->assertDatabaseHas('posts', ['title' => 'Hello World', 'text' => 'Lorem Ipsum']);
        $this->assertDatabaseHas('comments', ['text' => 'Lorem Ipsum', 'date' => $now]);

        $blog = Blog::first();
        $this->assertSame($blog->owner->email->domain(), 'example.com');
    }
}
