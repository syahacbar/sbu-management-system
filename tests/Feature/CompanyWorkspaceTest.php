<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyWorkspaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_records_are_scoped_to_the_open_company(): void
    {
        $admin = User::factory()->create();
        $companyA = Company::create(['name' => 'PT Alpha', 'is_active' => true]);
        $companyB = Company::create(['name' => 'PT Beta', 'is_active' => true]);

        $this->actingAs($admin)
            ->post(route('companies.workspace.directors.store', $companyA), [
                'code' => 'DIR-001',
                'name' => 'Direktur Alpha',
                'status' => 'aktif',
                'record_date' => null,
                'amount' => null,
                'is_active' => '1',
                'sort_order' => '1',
                'description' => 'Milik Alpha',
            ])
            ->assertRedirect(route('companies.workspace.directors.index', $companyA));

        $director = $companyA->directors()->firstOrFail();

        $this->actingAs($admin)
            ->get(route('companies.workspace.directors.index', $companyA))
            ->assertOk()
            ->assertSee('Direktur Alpha');

        $this->actingAs($admin)
            ->get(route('companies.workspace.directors.index', $companyB))
            ->assertOk()
            ->assertDontSee('Direktur Alpha');

        $this->actingAs($admin)
            ->get(route('companies.workspace.directors.edit', [$companyB, $director]))
            ->assertNotFound();
    }
}
