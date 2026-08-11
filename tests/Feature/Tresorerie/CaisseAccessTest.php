<?php

namespace Tests\Feature\Tresorerie;

use App\Models\Cotisation;
use App\Models\Depense;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CaisseAccessTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_simple_tresorier_na_pas_acces_a_la_caisse(): void
    {
        $tresorier = User::factory()->create(['is_tresorier' => true]);

        $response = $this->actingAs($tresorier)->get(route('tresorerie.caisse.index'));

        $response->assertForbidden();
    }

    #[Test]
    public function le_chef_tresorier_a_acces_a_la_caisse(): void
    {
        $chef = User::factory()->create(['is_chef_tresorier' => true]);

        $response = $this->actingAs($chef)->get(route('tresorerie.caisse.index'));

        $response->assertOk();
    }

    #[Test]
    public function le_commissaire_a_acces_a_la_caisse(): void
    {
        $commissaire = User::factory()->create(['is_commissaire_comptes' => true]);

        $response = $this->actingAs($commissaire)->get(route('tresorerie.caisse.index'));

        $response->assertOk();
    }

    #[Test]
    public function le_solde_est_le_total_des_cotisations_payees_moins_le_total_des_depenses(): void
    {
        $chef = User::factory()->create(['is_chef_tresorier' => true]);
        $membre = Membre::factory()->create();

        Cotisation::create([
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 50,
            'reste' => 0,
            'date_paiement' => now(),
            'created_by' => $chef->id,
        ]);

        $depense = Depense::create([
            'nom_evenement' => 'Sortie',
            'date_depense' => now(),
            'created_by' => $chef->id,
            'montant_total' => 0,
        ]);
        $depense->lignes()->create(['designation' => 'Transport', 'montant' => 30]);
        $depense->recalculerTotal();
        $depense->save();

        $response = $this->actingAs($chef)->get(route('tresorerie.caisse.index'));

        $response->assertOk();
        $response->assertViewHas('solde', 20.0);
        $response->assertViewHas('totalCotisations', 50.0);
        $response->assertViewHas('totalDepenses', 30.0);
    }
}
