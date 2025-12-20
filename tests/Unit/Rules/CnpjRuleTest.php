<?php

namespace Tests\Unit\Rules;

use App\Rules\Cnpj;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CnpjRuleTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function test_should_not_return_errors_when_the_cnpj_is_valid(): void
    {
        $cnpjs = ['85.022.795/0001-91', '16.226.848/0001-73', $this->faker->cnpj()];

        foreach ($cnpjs as $cnpj) {
            $this->assertTrue(Validator::make(
                ['cnpj' => $cnpj],
                ['cnpj' => new Cnpj]
            )->passes());
        }
    }

    public function test_should_return_an_error_when_the_cnpj_size_is_invalid(): void
    {
        $cnpj = $this->faker->cnpj() . 1;

        $this->assertFalse(Validator::make(
            ['cnpj' => $cnpj],
            ['cnpj' => new Cnpj]
        )->passes(), "{$cnpj} should be valid");
    }

    public function test_should_return_an_error_when_the_cnpj_is_sequential(): void
    {
        $cnpj = '11.111.111/1111-11';

        $this->assertFalse(Validator::make(
            ['cnpj' => $cnpj],
            ['cnpj' => new Cnpj]
        )->passes(), "{$cnpj} should be valid");
    }

    public function test_should_return_an_error_when_the_cnpj_is_invalid(): void
    {
        $cnpjs = ['78.254.743/0001-84', '11.411.068/0001-79', '56.979.460/0001-67'];

        foreach ($cnpjs as $cnpj) {
            $this->assertFalse(Validator::make(
                ['cnpj' => $cnpj],
                ['cnpj' => new Cnpj]
            )->passes(), "{$cnpj} should be valid");
        }
    }
}
