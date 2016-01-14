<?php

namespace Waavi\ValueObjects\Test;

use Waavi\ValueObjects\Test\Models\Account;
use Waavi\ValueObjects\Test\Models\Address;
use Waavi\ValueObjects\Test\Models\Email;

class FillableTest extends TestCase
{
    /**
     *  @test
     */
    public function it_only_saves_fillable_fields()
    {
        $address = new Address([
            'street'       => 'Gran vía',
            'city'         => 'Madrid',
            'country'      => 'Spain',
            'non-fillable' => 'Error',
        ]);
        $this->assertEquals(
            json_encode(['street' => 'Gran vía', 'city' => 'Madrid', 'country' => 'Spain']),
            (string) $address
        );
    }

    /**
     *  @test
     */
    public function single_variable_value_objects_can_be_set_as_strings()
    {
        $account        = new Account;
        $account->email = 'info@waavi.com';
        $this->assertInstanceOf(Email::class, $account->email);
        $this->assertEquals('info@waavi.com', (string) $account->email);
    }
}
