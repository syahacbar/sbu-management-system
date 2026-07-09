<?php

namespace Tests\Feature;

use App\Models\MasterSbuClassification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterSbuClassificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_sbu_classification_reference(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('master.classifications.store'), [
            'code' => 'BG',
            'name' => 'Bangunan Gedung',
            'description' => 'Klasifikasi pekerjaan bangunan gedung.',
            'is_active' => '1',
            'sort_order' => '10',
        ]);

        $classification = MasterSbuClassification::query()->firstOrFail();

        $response->assertRedirect(route('master.classifications.show', $classification));
        $this->assertSame('BG', $classification->code);
        $this->assertTrue($classification->is_active);

        $this->actingAs($admin)
            ->get(route('master.classifications.index', ['search' => 'Bangunan', 'status' => 'active']))
            ->assertOk()
            ->assertSee('Bangunan Gedung');

        $this->actingAs($admin)
            ->put(route('master.classifications.update', $classification), [
                'code' => 'SI',
                'name' => 'Spesialis',
                'description' => null,
                'is_active' => '0',
                'sort_order' => '20',
            ])
            ->assertRedirect(route('master.classifications.show', $classification));

        $classification->refresh();

        $this->assertSame('SI', $classification->code);
        $this->assertFalse($classification->is_active);
    }

    public function test_sbu_classification_code_must_be_unique(): void
    {
        $admin = User::factory()->create();

        MasterSbuClassification::create([
            'code' => 'BG',
            'name' => 'Bangunan Gedung',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('master.classifications.store'), [
                'code' => 'BG',
                'name' => 'Duplikat Klasifikasi',
                'description' => null,
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('code');
    }
}
