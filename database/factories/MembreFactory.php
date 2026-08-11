<?php

namespace Database\Factories;

use App\Models\Departement;
use App\Models\Pays;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Membre>
 */
class MembreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'matricule' => strtoupper(Str::random(8)),
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'sexe' => fake()->randomElement(['M', 'F']),
            'iddep' => Departement::factory(),
            'idpays' => Pays::factory(),
            'annee_adhesion' => fake()->numberBetween(2018, (int) date('Y')),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
