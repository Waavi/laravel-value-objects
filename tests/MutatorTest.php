<?php

namespace Waavi\ValueObjects\Test;

use Waavi\ValueObjects\Test\Models\Address;
use Waavi\ValueObjects\Test\Models\Email;

class MutatorTest extends TestCase
{
    /**
     *  @test
     */
    public function single_field_value_objects_have_unique_attribute()
    {
        $email = new Email('info@waavi.com');
        $this->assertEquals('info@waavi.com', $email->value);
    }

    /**
     *  @test
     */
    public function can_access_attributes_by_name()
    {
        $address = new Address([
            'street'  => 'Gran vía',
            'city'    => 'Madrid',
            'country' => 'Spain',
        ]);
        $this->assertEquals('Gran vía', $address->street);
    }

    /**
     *  @test
     */
    public function get_attribute_mutator()
    {
        $address = new Address([
            'street'  => 'Gran vía',
            'city'    => 'Madrid',
            'country' => 'Spain',
        ]);
        $this->assertEquals('Gran vía, Madrid, Spain', $address->formatted);
    }

    /**
     *  @test
     */
    public function set_attribute_mutator()
    {
        $address = new Address([
            'street'  => 'Gran vía',
            'city'    => 'Madrid',
            'country' => '   Spain',
        ]);
        $this->assertEquals('Spain', $address->country);
    }

    /**
     *  @test
     */
    public function get_non_existing_attribute_return_null()
    {
        $address = new Address([
            'street'  => 'Gran vía',
            'city'    => 'madrid',
            'country' => 'Spain',
        ]);
        $this->assertNull($address->streetNumber);
    }
}
