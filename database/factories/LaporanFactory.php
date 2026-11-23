<?php

namespace Database\Factories;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Laporan>
 */
class LaporanFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pelapor_id' => User::factory(),
            'kategori' => fake()->randomElement(array_keys(Laporan::KATEGORI)),
            'judul' => fake()->sentence(6),
            'detail' => fake()->paragraph(3),
            'tanggal' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'status' => 'pending',
        ];
    }

    public function berstatus(string $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
