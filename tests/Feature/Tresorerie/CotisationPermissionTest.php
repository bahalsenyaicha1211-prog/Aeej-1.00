<?php

namespace Tests\Feature\Tresorerie;

use App\Models\BureauMembre;
use App\Models\Cotisation;
use App\Models\CotisationConfig;
use App\Models\Membre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CotisationPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function tresorier(): User
    {
        return User::factory()->create(['is_tresorier' => true]);
    }

    private function chefTresorier(): User
    {
        return User::factory()->create(['is_chef_tresorier' => true]);
    }

    #[Test]
    public function un_tresorier_ne_voit_que_les_cotisations_quil_a_lui_meme_enregistrees(): void
    {
        $moi = $this->tresorier();
        $unAutre = $this->tresorier();

        $membre1 = Membre::factory()->create();
        $membre2 = Membre::factory()->create();

        Cotisation::create([
            'matricule' => $membre1->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 50,
            'reste' => 0,
            'date_paiement' => now(),
            'created_by' => $moi->id,
        ]);
        Cotisation::create([
            'matricule' => $membre2->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 20,
            'reste' => 30,
            'date_paiement' => now(),
            'created_by' => $unAutre->id,
        ]);

        $response = $this->actingAs($moi)->get(route('tresorerie.cotisations.index'));

        $response->assertOk();
        $response->assertViewHas('cotisations', function ($cotisations) use ($membre1, $membre2) {
            $matricules = $cotisations->pluck('matricule')->all();

            return in_array($membre1->matricule, $matricules, true)
                && !in_array($membre2->matricule, $matricules, true);
        });
    }

    #[Test]
    public function le_chef_tresorier_voit_les_cotisations_de_tous_les_tresoriers(): void
    {
        $chef = $this->chefTresorier();
        $tresorier = $this->tresorier();

        $membre = Membre::factory()->create();
        Cotisation::create([
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 50,
            'reste' => 0,
            'date_paiement' => now(),
            'created_by' => $tresorier->id,
        ]);

        $response = $this->actingAs($chef)->get(route('tresorerie.cotisations.index'));

        $response->assertOk();
        $response->assertViewHas('cotisations', fn ($cotisations) => $cotisations->total() === 1);
    }

    #[Test]
    public function un_tresorier_ne_peut_pas_modifier_lenregistrement_dun_autre_tresorier(): void
    {
        $moi = $this->tresorier();
        $unAutre = $this->tresorier();
        $membre = Membre::factory()->create();

        $cotisation = Cotisation::create([
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 20,
            'reste' => 30,
            'date_paiement' => now(),
            'created_by' => $unAutre->id,
        ]);

        $response = $this->actingAs($moi)->put(route('tresorerie.cotisations.update', $cotisation), [
            'montant_paye' => 50,
            'date_paiement' => now()->toDateString(),
        ]);

        $response->assertForbidden();
        $this->assertEquals(20, $cotisation->fresh()->montant_paye);
    }

    #[Test]
    public function le_chef_tresorier_peut_modifier_lenregistrement_dun_tresorier(): void
    {
        $chef = $this->chefTresorier();
        $tresorier = $this->tresorier();
        $membre = Membre::factory()->create();

        $cotisation = Cotisation::create([
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 20,
            'reste' => 30,
            'date_paiement' => now(),
            'created_by' => $tresorier->id,
        ]);

        $response = $this->actingAs($chef)->put(route('tresorerie.cotisations.update', $cotisation), [
            'montant_paye' => 50,
            'date_paiement' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('tresorerie.cotisations.index'));
        $this->assertEquals(50, $cotisation->fresh()->montant_paye);
        $this->assertEquals(0, $cotisation->fresh()->reste);
    }

    #[Test]
    public function un_simple_membre_na_pas_acces_a_lespace_tresorerie(): void
    {
        $membreSimple = User::factory()->create();

        $response = $this->actingAs($membreSimple)->get(route('tresorerie.cotisations.index'));

        $response->assertForbidden();
    }

    #[Test]
    public function le_montant_paye_ne_peut_pas_depasser_le_montant_du_a_la_creation(): void
    {
        $tresorier = $this->tresorier();
        $membre = Membre::factory()->create();
        CotisationConfig::create(['annee' => 2026, 'montant_membre' => 50, 'montant_bureau' => 80]);

        $response = $this->actingAs($tresorier)->post(route('tresorerie.cotisations.store'), [
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'montant_paye' => 999,
            'date_paiement' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('montant_paye');
        $this->assertDatabaseCount('cotisations', 0);
    }

    #[Test]
    public function impossible_denregistrer_deux_cotisations_pour_le_meme_membre_la_meme_annee(): void
    {
        $tresorier = $this->tresorier();
        $membre = Membre::factory()->create();
        CotisationConfig::create(['annee' => 2026, 'montant_membre' => 50, 'montant_bureau' => 80]);

        Cotisation::create([
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'categorie' => 'membre',
            'montant_du' => 50,
            'montant_paye' => 20,
            'reste' => 30,
            'date_paiement' => now(),
            'created_by' => $tresorier->id,
        ]);

        $response = $this->actingAs($tresorier)->post(route('tresorerie.cotisations.store'), [
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'montant_paye' => 10,
            'date_paiement' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('matricule');
        $this->assertDatabaseCount('cotisations', 1);
    }

    #[Test]
    public function le_montant_du_est_celui_du_bureau_pour_un_membre_du_bureau(): void
    {
        $tresorier = $this->tresorier();
        $membre = Membre::factory()->create();
        BureauMembre::create([
            'matricule' => $membre->matricule,
            'poste' => 'Secrétaire général',
            'is_actif' => true,
        ]);
        CotisationConfig::create(['annee' => 2026, 'montant_membre' => 50, 'montant_bureau' => 80]);

        $response = $this->actingAs($tresorier)->post(route('tresorerie.cotisations.store'), [
            'matricule' => $membre->matricule,
            'annee' => 2026,
            'montant_paye' => 80,
            'date_paiement' => now()->toDateString(),
        ]);

        $response->assertRedirect(route('tresorerie.cotisations.index'));
        $cotisation = Cotisation::where('matricule', $membre->matricule)->firstOrFail();
        $this->assertEquals('bureau', $cotisation->categorie);
        $this->assertEquals(80, $cotisation->montant_du);
    }
}
