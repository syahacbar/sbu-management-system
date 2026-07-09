<?php

namespace Tests\Feature;

use App\Models\MasterKbli;
use App\Models\MasterSbuClassification;
use App\Models\MasterSbuScheme;
use App\Models\MasterSbuSubclassification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterSbuSchemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_sbu_scheme_reference(): void
    {
        $admin = User::factory()->create();
        [$kbli, $classification, $subclassification] = $this->references();

        $response = $this->actingAs($admin)->post(route('master.schemes.store'), [
            'master_kbli_id' => $kbli->id,
            'master_sbu_classification_id' => $classification->id,
            'master_sbu_subclassification_id' => $subclassification->id,
            'scheme_code' => 'SK-BG001-K',
            'scheme_name' => 'Skema Gedung Hunian Kecil',
            'qualification' => 'Kecil',
            'description' => 'Skema dummy.',
            'is_active' => '1',
            'sort_order' => '10',
        ]);

        $scheme = MasterSbuScheme::query()->firstOrFail();

        $response->assertRedirect(route('master.schemes.show', $scheme));
        $this->assertSame($kbli->id, $scheme->master_kbli_id);
        $this->assertSame('Kecil', $scheme->qualification);

        $this->actingAs($admin)
            ->get(route('master.schemes.index', [
                'search' => 'BG001',
                'qualification' => 'Kecil',
                'status' => 'active',
            ]))
            ->assertOk()
            ->assertSee('41011')
            ->assertSee('BG')
            ->assertSee('BG001')
            ->assertSee('Kecil');
    }

    public function test_subclassification_must_match_selected_classification(): void
    {
        $admin = User::factory()->create();
        [$kbli, $classification] = $this->references();
        $otherClassification = MasterSbuClassification::create([
            'code' => 'BS',
            'name' => 'Bangunan Sipil',
            'is_active' => true,
            'sort_order' => 20,
        ]);
        $otherSubclassification = MasterSbuSubclassification::create([
            'master_sbu_classification_id' => $otherClassification->id,
            'code' => 'BS001',
            'name' => 'Jalan Raya',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $this->actingAs($admin)
            ->post(route('master.schemes.store'), [
                'master_kbli_id' => $kbli->id,
                'master_sbu_classification_id' => $classification->id,
                'master_sbu_subclassification_id' => $otherSubclassification->id,
                'scheme_code' => 'SK-BAD',
                'scheme_name' => 'Skema Tidak Valid',
                'qualification' => 'Kecil',
                'description' => null,
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('master_sbu_subclassification_id');
    }

    /**
     * @return array{0: MasterKbli, 1: MasterSbuClassification, 2: MasterSbuSubclassification}
     */
    private function references(): array
    {
        $kbli = MasterKbli::create([
            'code' => '41011',
            'name' => 'Konstruksi Gedung Hunian',
            'is_active' => true,
            'sort_order' => 10,
        ]);
        $classification = MasterSbuClassification::create([
            'code' => 'BG',
            'name' => 'Bangunan Gedung',
            'is_active' => true,
            'sort_order' => 10,
        ]);
        $subclassification = MasterSbuSubclassification::create([
            'master_sbu_classification_id' => $classification->id,
            'code' => 'BG001',
            'name' => 'Gedung Hunian',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        return [$kbli, $classification, $subclassification];
    }
}
