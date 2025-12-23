<?php

namespace Database\Factories;

use App\Enum\Transaction\TransactionStatus;
use App\Enum\Transaction\TransactionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => TransactionStatus::COMPLETED->value,
            'value' => 150.27,
            'payer_id' => UserFactory::new()->consumer()->create(),
            'payee_id' => UserFactory::new()->consumer()->create(),
        ];
    }

    /**
     * Indicate that the transaction is a deposit.
     */
    public function deposit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::DEPOSIT->value,
        ]);
    }

    /**
     * Indicate that the transaction is a transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TransactionType::TRANSFER->value,
        ]);
    }
}
