<?php

namespace Tests\Feature;

use App\Models\MasterSbuClassification;
use App\Models\MasterSbuSubclassification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterSbuSubclassificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_sbu_subclassification_reference(): void
    {
        $admin = User::factory()->create();
        $classification = MasterSbuClassification::create([
            'code' => 'BG',
            'name' => 'Bangunan Gedung',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($admin)->post(route('master.subclassifications.store'), [
            'master_sbu_classification_id' => $classification->id,
            'code' => 'BG001',
            'name' => 'Gedung Hunian',
            'description' => 'Subklasifikasi gedung hunian.',
            'is_active' => '1',
            'sort_order' => '10',
        ]);

        $subclassification = MasterSbuSubclassification::query()->firstOrFail();

        $response->assertRedirect(route('master.subclassifications.show', $subclassification));
        $this->assertSame($classification->id, $subclassification->master_sbu_classification_id);
        $this->assertSame('BG001', $subclassification->code);

        $this->actingAs($admin)
            ->get(route('master.subclassifications.index', [
                'search' => 'Hunian',
                'classification_id' => $classification->id,
                'status' => 'active',
            ]))
            ->assertOk()
            ->assertSee('BG')
            ->assertSee('BG001')
            ->assertSee('Gedung Hunian');

        $this->actingAs($admin)
            ->put(route('master.subclassifications.update', $subclassification), [
                'master_sbu_classification_id' => $classification->id,
                'code' => 'BG002',
                'name' => 'Gedung Perkantoran',
                'description' => null,
                'is_active' => '0',
                'sort_order' => '20',
            ])
            ->assertRedirect(route('master.subclassifications.show', $subclassification));

        $subclassification->refresh();

        $this->assertSame('BG002', $subclassification->code);
        $this->assertFalse($subclassification->is_active);
    }

    public function test_sbu_subclassification_code_must_be_unique(): void
    {
        $admin = User::factory()->create();
        $classification = MasterSbuClassification::create([
            'code' => 'BG',
            'name' => 'Bangunan Gedung',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        MasterSbuSubclassification::create([
            'master_sbu_classification_id' => $classification->id,
            'code' => 'BG001',
            'name' => 'Gedung Hunian',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('master.subclassifications.store'), [
                'master_sbu_classification_id' => $classification->id,
                'code' => 'BG001',
                'name' => 'Duplikat Subklasifikasi',
                'description' => null,
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('code');
    }
}
