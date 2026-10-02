<?php

namespace Tests\Feature;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DemographicAnalysisTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.demographic-analysis.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_super_admin_can_access_demographic_analysis(): void
    {
        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('admin.demographic-analysis.index'));

        $response->assertOk();
        $response->assertViewIs('admin.demographic-analysis.index');
        $response->assertSee('Analisis Karakteristik Demografi Pegawai');
        $response->assertSee('Distribusi Rentang Usia');
        $response->assertSee('Distribusi Jenis Kelamin');
        $response->assertSee('Distribusi Tingkat Pendapatan');
        $response->assertSee('Distribusi Status Kepegawaian');
        $response->assertSee('Distribusi Jenjang Pendidikan');
    }

    public function test_admin_rs_can_access_demographic_analysis(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin-rs']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get(route('admin.demographic-analysis.index'));

        $response->assertOk();
        $response->assertViewIs('admin.demographic-analysis.index');
    }
}
