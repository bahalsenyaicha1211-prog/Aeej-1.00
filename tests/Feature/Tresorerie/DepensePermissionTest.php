<?php

namespace Tests\Feature\Tresorerie;

use App\Models\Depense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepensePermissionTest extends TestCase
{
    use RefreshDatabase;

    private function commissaire(): User
    {
        return User::factory()->create(['is_commissaire_comptes' => true]);
    }

    #[Test]
    public function un_tresorier_qui_nest_pas_commissaire_na_pas_acces_aux_depenses(): void
    {
        $tresorier = User::factory()->create(['is_tresorier' => true]);

        $response = $this->actingAs($tresorier)->get(route('tresorerie.depenses.index'));

        $response->assertForbidden();
    }

    #[Test]
    public function le_commissaire_peut_enregistrer_une_depense_multi_lignes(): void
    {
        $commissaire = $this->commissaire();

        $response = $this->actingAs($commissaire)->post(route('tresorerie.depenses.store'), [
            'nom_evenement' => 'Journée culturelle',
            'date_depense' => now()->toDateString(),
            'lignes' => [
                ['designation' => 'Location salle', 'montant' => 300],
                ['designation' => 'Buffet', 'montant' => 150.5],
            ],
        ]);

        $response->assertRedirect(route('tresorerie.depenses.index'));

        $depense = Depense::firstOrFail();
        $this->assertEquals(450.5, $depense->montant_total);
        $this->assertCount(2, $depense->lignes);
    }

    #[Test]
    public function modifier_une_depense_remplace_les_lignes_et_recalcule_le_total(): void
    {
        $commissaire = $this->commissaire();

        $depense = Depense::create([
            'nom_evenement' => 'Ancien événement',
            'date_depense' => now(),
            'created_by' => $commissaire->id,
            'montant_total' => 0,
        ]);
        $depense->lignes()->create(['designation' => 'Ancienne ligne', 'montant' => 999]);
        $depense->recalculerTotal();
        $depense->save();

        $response = $this->actingAs($commissaire)->put(route('tresorerie.depenses.update', $depense), [
            'nom_evenement' => 'Événement corrigé',
            'date_depense' => now()->toDateString(),
            'lignes' => [
                ['designation' => 'Transport', 'montant' => 60],
            ],
        ]);

        $response->assertRedirect(route('tresorerie.depenses.index'));

        $depense->refresh();
        $this->assertEquals(60, $depense->montant_total);
        $this->assertCount(1, $depense->lignes);
    }

    #[Test]
    public function un_simple_membre_na_pas_acces_a_lespace_tresorerie(): void
    {
        $membreSimple = User::factory()->create();

        $response = $this->actingAs($membreSimple)->get(route('tresorerie.depenses.index'));

        $response->assertForbidden();
    }
}
