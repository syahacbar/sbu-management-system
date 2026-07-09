<?php

namespace Tests\Feature;

use App\Models\MasterKbli;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterKbliTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_kbli_reference(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('master.kbli.store'), [
            'code' => '41011',
            'name' => 'Konstruksi Gedung Hunian',
            'description' => 'Referensi global.',
            'is_active' => '1',
            'sort_order' => '10',
        ]);

        $kbli = MasterKbli::query()->firstOrFail();

        $response->assertRedirect(route('master.kbli.show', $kbli));
        $this->assertSame('41011', $kbli->code);
        $this->assertTrue($kbli->is_active);

        $this->actingAs($admin)
            ->get(route('master.kbli.index', ['search' => '41011', 'status' => 'active']))
            ->assertOk()
            ->assertSee('Konstruksi Gedung Hunian');

        $this->actingAs($admin)
            ->put(route('master.kbli.update', $kbli), [
                'code' => '41012',
                'name' => 'Konstruksi Gedung Perkantoran',
                'description' => null,
                'is_active' => '0',
                'sort_order' => '20',
            ])
            ->assertRedirect(route('master.kbli.show', $kbli));

        $kbli->refresh();

        $this->assertSame('41012', $kbli->code);
        $this->assertFalse($kbli->is_active);
    }

    public function test_kbli_code_must_be_unique(): void
    {
        $admin = User::factory()->create();

        MasterKbli::create([
            'code' => '41011',
            'name' => 'Konstruksi Gedung Hunian',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->post(route('master.kbli.store'), [
                'code' => '41011',
                'name' => 'Duplikat KBLI',
                'description' => null,
                'is_active' => '1',
                'sort_order' => '0',
            ])
            ->assertSessionHasErrors('code');
    }
}
