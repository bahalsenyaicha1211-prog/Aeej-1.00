<?php

namespace Tests\Unit;

use App\Models\Cotisation;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CotisationCalculTest extends TestCase
{
    #[Test]
    public function le_reste_est_la_difference_entre_le_du_et_le_paye(): void
    {
        $cotisation = new Cotisation([
            'montant_du' => 50,
            'montant_paye' => 30,
        ]);

        $cotisation->recalculerReste();

        $this->assertEquals(20, $cotisation->reste);
    }

    #[Test]
    public function le_reste_est_nul_quand_tout_est_paye(): void
    {
        $cotisation = new Cotisation([
            'montant_du' => 50,
            'montant_paye' => 50,
        ]);

        $cotisation->recalculerReste();

        $this->assertEquals(0, $cotisation->reste);
    }

    #[Test]
    public function le_reste_ne_devient_jamais_negatif_meme_en_cas_de_trop_percu(): void
    {
        $cotisation = new Cotisation([
            'montant_du' => 50,
            'montant_paye' => 80,
        ]);

        $cotisation->recalculerReste();

        $this->assertEquals(0, $cotisation->reste);
    }
}
