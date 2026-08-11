<?php

namespace Tests\Unit;

use App\Models\Depense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepenseCalculTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function le_total_est_la_somme_des_lignes(): void
    {
        $depense = Depense::create([
            'nom_evenement' => 'Sortie culturelle',
            'date_depense' => now(),
            'created_by' => $this->creerCommissaire()->id,
            'montant_total' => 0,
        ]);

        $depense->lignes()->create(['designation' => 'Transport', 'montant' => 120.50]);
        $depense->lignes()->create(['designation' => 'Restauration', 'montant' => 89.75]);

        $depense->recalculerTotal();

        $this->assertEquals(210.25, $depense->montant_total);
    }

    #[Test]
    public function le_total_est_nul_sans_aucune_ligne(): void
    {
        $depense = Depense::create([
            'nom_evenement' => 'Événement annulé',
            'date_depense' => now(),
            'created_by' => $this->creerCommissaire()->id,
            'montant_total' => 999,
        ]);

        $depense->recalculerTotal();

        $this->assertEquals(0, $depense->montant_total);
    }

    #[Test]
    public function le_total_se_met_a_jour_quand_les_lignes_changent(): void
    {
        $depense = Depense::create([
            'nom_evenement' => 'Conférence',
            'date_depense' => now(),
            'created_by' => $this->creerCommissaire()->id,
            'montant_total' => 0,
        ]);

        $depense->lignes()->create(['designation' => 'Salle', 'montant' => 200]);
        $depense->recalculerTotal();
        $depense->save();
        $this->assertEquals(200, $depense->fresh()->montant_total);

        // Remplacement des lignes, comme le fait DepenseController::update()
        $depense->lignes()->delete();
        $depense->lignes()->create(['designation' => 'Traiteur', 'montant' => 350]);
        $depense->lignes()->create(['designation' => 'Décoration', 'montant' => 45]);
        $depense->recalculerTotal();
        $depense->save();

        $this->assertEquals(395, $depense->fresh()->montant_total);
    }

    private function creerCommissaire(): \App\Models\User
    {
        return \App\Models\User::factory()->create();
    }
}
