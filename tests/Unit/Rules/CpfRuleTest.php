<?php

namespace Tests\Unit\Rules;

use App\Rules\CpfRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CpfRuleTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create('pt_BR');
    }

    public function test_should_not_return_errors_when_the_cpf_is_valid(): void
    {
        $cpfs = [$this->faker->cpf(), $this->faker->cpf(), $this->faker->cpf()];

        foreach ($cpfs as $cpf) {
            $this->assertTrue(Validator::make(
                ['cpf' => $cpf],
                ['cpf' => new CpfRule]
            )->passes());
        }
    }

    public function test_should_return_an_error_when_the_cpf_size_is_invalid(): void
    {
        $cpf = $this->faker->cpf() . 1;

        $this->assertFalse(Validator::make(
            ['cpf' => $cpf],
            ['cpf' => new CpfRule]
        )->passes(), "{$cpf} should be valid");
    }

    public function test_should_return_an_error_when_the_cpf_is_sequential(): void
    {
        $cpf = '111.111.111-11';

        $this->assertFalse(Validator::make(
            ['cpf' => $cpf],
            ['cpf' => new CpfRule]
        )->passes(), "{$cpf} should be valid");
    }

    public function test_should_return_an_error_when_the_cpf_is_invalid(): void
    {
        $cpfs = ['461.760.430-58', '935.100.530-25', '807.283.430-54'];

        foreach ($cpfs as $cpf) {
            $this->assertFalse(Validator::make(
                ['cpf' => $cpf],
                ['cpf' => new CpfRule]
            )->passes(), "{$cpf} should be valid");
        }
    }
}
