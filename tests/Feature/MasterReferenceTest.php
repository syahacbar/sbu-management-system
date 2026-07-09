<?php

namespace Tests\Feature;

use App\Models\Master\Qualification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterReferenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_master_reference(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post(route('master.qualifications.store'), [
                'code' => 'K',
                'name' => 'Kecil',
                'is_active' => '1',
                'sort_order' => '10',
                'description' => 'Referensi kualifikasi.',
            ])
            ->assertRedirect(route('master.qualifications.index'));

        $qualification = Qualification::query()->firstOrFail();

        $this->assertSame('K', $qualification->code);
        $this->assertTrue($qualification->is_active);

        $this->actingAs($admin)
            ->put(route('master.qualifications.update', $qualification), [
                'code' => 'M',
                'name' => 'Menengah',
                'is_active' => '0',
                'sort_order' => '20',
                'description' => null,
            ])
            ->assertRedirect(route('master.qualifications.index'));

        $qualification->refresh();

        $this->assertSame('M', $qualification->code);
        $this->assertFalse($qualification->is_active);

        $this->actingAs($admin)
            ->delete(route('master.qualifications.destroy', $qualification))
            ->assertRedirect(route('master.qualifications.index'));

        $this->assertDatabaseMissing('master_qualifications', [
            'id' => $qualification->id,
        ]);
    }
}
