<?php

namespace Waavi\ValueObjects\Test;

use Waavi\ValueObjects\Test\Models\Account;
use Waavi\ValueObjects\Test\Models\Address;
use Waavi\ValueObjects\Test\Models\Email;

class CastingTest extends TestCase
{
    /**
     *  @test
     */
    public function it_saves_simple_value_objects()
    {
        $name    = 'example';
        $email   = 'info@waavi.com';
        $account = Account::create([
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
        $account = Account::create([
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
}
