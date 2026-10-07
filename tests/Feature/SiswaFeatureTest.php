<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'email' => 'admin@tkharapanbangsa.sch.id',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_dashboard_or_siswa(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('siswa.index'))->assertRedirect(route('login'));
        $this->get(route('kelas.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_view_dashboard_with_metrics(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Siswa TK Harapan Bangsa');
        $response->assertSee('Siswa Aktif');
    }

    public function test_admin_can_create_siswa_with_parent_records(): void
    {
        $kelas = Kelas::create([
            'nama' => 'TK A1',
            'tingkat' => 'A',
            'tahun_ajaran' => '2025/2026',
            'wali_kelas' => 'Ibu Guru',
        ]);

        $payload = [
            'nama' => 'Bintang Pratama',
            'kelas_id' => $kelas->id,
            'nipd' => 'PD2025001',
            'jk' => 'L',
            'nisn' => '0098765432',
            'nik' => '3201011122330001',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2020-04-12',
            'agama' => 'Islam',
            'alamat' => 'Jl. Kenanga No. 10',
            'rt' => '01',
            'rw' => '02',
            'angkatan' => 2025,
            'status' => 'aktif',
            'ayah' => [
                'nama' => 'Bambang Pratama',
                'tahun_lahir' => 1985,
                'jenjang_pendidikan' => 'S1/D4',
                'pekerjaan' => 'Karyawan Swasta',
                'penghasilan' => 'Rp 5.000.000 - Rp 10.000.000',
                'nik' => '3201011122330002',
            ],
            'ibu' => [
                'nama' => 'Siti Aminah',
                'tahun_lahir' => 1988,
                'jenjang_pendidikan' => 'SMA/SMK',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'penghasilan' => 'Tidak Berpenghasilan',
                'nik' => '3201011122330003',
            ],
        ];

        $response = $this->actingAs($this->admin)->post(route('siswa.store'), $payload);

        $response->assertRedirect(route('siswa.index'));
        $this->assertDatabaseHas('siswas', [
            'nama' => 'Bintang Pratama',
            'nisn' => '0098765432',
            'kelas_id' => $kelas->id,
        ]);

        $siswa = Siswa::where('nisn', '0098765432')->first();
        $this->assertNotNull($siswa);
        $this->assertDatabaseHas('orang_tuas', [
            'siswa_id' => $siswa->id,
            'hubungan' => 'ayah',
            'nama' => 'Bambang Pratama',
        ]);
        $this->assertDatabaseHas('orang_tuas', [
            'siswa_id' => $siswa->id,
            'hubungan' => 'ibu',
            'nama' => 'Siti Aminah',
        ]);
    }

    public function test_admin_can_update_siswa(): void
    {
        $siswa = Siswa::create([
            'nama' => 'Nama Lama',
            'jk' => 'P',
            'angkatan' => 2024,
            'status' => 'aktif',
        ]);

        $payload = [
            'nama' => 'Nama Baru',
            'jk' => 'P',
            'angkatan' => 2024,
            'status' => 'aktif',
        ];

        $response = $this->actingAs($this->admin)->put(route('siswa.update', $siswa), $payload);

        $response->assertRedirect(route('siswa.show', $siswa));
        $this->assertDatabaseHas('siswas', [
            'id' => $siswa->id,
            'nama' => 'Nama Baru',
        ]);
    }

    public function test_admin_can_soft_delete_siswa(): void
    {
        $siswa = Siswa::create([
            'nama' => 'Siswa Dihapus',
            'jk' => 'L',
            'angkatan' => 2024,
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('siswa.destroy', $siswa));

        $response->assertRedirect(route('siswa.index'));
        $this->assertSoftDeleted('siswas', [
            'id' => $siswa->id,
        ]);
    }

    public function test_admin_can_graduate_students(): void
    {
        $kelas = Kelas::create([
            'nama' => 'TK B1',
            'tingkat' => 'B',
            'tahun_ajaran' => '2024/2025',
        ]);

        $s1 = Siswa::create([
            'kelas_id' => $kelas->id,
            'nama' => 'Siswa Lulus 1',
            'jk' => 'L',
            'angkatan' => 2023,
            'status' => 'aktif',
        ]);

        $s2 = Siswa::create([
            'kelas_id' => $kelas->id,
            'nama' => 'Siswa Lulus 2',
            'jk' => 'P',
            'angkatan' => 2023,
            'status' => 'aktif',
        ]);

        $payload = [
            'siswa_ids' => [$s1->id, $s2->id],
            'tahun_lulus' => 2025,
        ];

        $response = $this->actingAs($this->admin)->post(route('siswa.luluskan'), $payload);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('siswas', [
            'id' => $s1->id,
            'status' => 'lulus',
            'tahun_lulus' => 2025,
            'kelas_id' => null,
        ]);
        $this->assertDatabaseHas('siswas', [
            'id' => $s2->id,
            'status' => 'lulus',
            'tahun_lulus' => 2025,
            'kelas_id' => null,
        ]);
    }

    public function test_admin_can_cancel_graduation(): void
    {
        $kelas = Kelas::create([
            'nama' => 'TK A1',
            'tingkat' => 'A',
            'tahun_ajaran' => '2025/2026',
        ]);

        $siswa = Siswa::create([
            'nama' => 'Alumni Kembali',
            'jk' => 'L',
            'angkatan' => 2023,
            'status' => 'lulus',
            'tahun_lulus' => 2025,
            'kelas_id' => null,
        ]);

        $payload = [
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
        ];

        $response = $this->actingAs($this->admin)->post(route('siswa.batalkan-lulus'), $payload);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('siswas', [
            'id' => $siswa->id,
            'status' => 'aktif',
            'tahun_lulus' => null,
            'kelas_id' => $kelas->id,
        ]);
    }

    public function test_admin_can_manage_kelas(): void
    {
        // Store
        $response = $this->actingAs($this->admin)->post(route('kelas.store'), [
            'nama' => 'TK Baru',
            'tingkat' => 'A',
            'tahun_ajaran' => '2025/2026',
            'wali_kelas' => 'Wali Kelas 1',
        ]);
        $response->assertRedirect(route('kelas.index'));
        $this->assertDatabaseHas('kelas', ['nama' => 'TK Baru']);

        $kelas = Kelas::where('nama', 'TK Baru')->first();

        // Update
        $this->actingAs($this->admin)->put(route('kelas.update', $kelas), [
            'nama' => 'TK Baru Updated',
            'tingkat' => 'B',
            'tahun_ajaran' => '2025/2026',
            'wali_kelas' => 'Wali Kelas 2',
        ]);
        $this->assertDatabaseHas('kelas', ['nama' => 'TK Baru Updated']);

        // Destroy
        $this->actingAs($this->admin)->delete(route('kelas.destroy', $kelas));
        $this->assertDatabaseMissing('kelas', ['id' => $kelas->id]);
    }

    public function test_admin_can_export_siswa(): void
    {
        $response = $this->actingAs($this->admin)->get(route('siswa.export'));

        $response->assertStatus(200);
        $this->assertStringContainsString('filename=', $response->headers->get('content-disposition'));
    }

    public function test_admin_can_export_selected_siswa_by_ids(): void
    {
        $siswa1 = Siswa::factory()->create(['nama' => 'Pilihan Satu']);
        $siswa2 = Siswa::factory()->create(['nama' => 'Pilihan Dua']);

        $response = $this->actingAs($this->admin)->post(route('siswa.export'), [
            'ids' => [$siswa1->id, $siswa2->id],
        ]);

        $response->assertStatus(200);
        $this->assertStringContainsString('filename=', $response->headers->get('content-disposition'));
    }

    public function test_admin_can_bulk_destroy_siswa(): void
    {
        $siswa1 = Siswa::factory()->create(['nama' => 'Hapus Massal 1']);
        $siswa2 = Siswa::factory()->create(['nama' => 'Hapus Massal 2']);

        $response = $this->actingAs($this->admin)->post(route('siswa.bulk-destroy'), [
            'ids' => [$siswa1->id, $siswa2->id],
        ]);

        $response->assertRedirect(route('siswa.index'));
        $this->assertSoftDeleted('siswas', ['id' => $siswa1->id]);
        $this->assertSoftDeleted('siswas', ['id' => $siswa2->id]);
    }
}
