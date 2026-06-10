<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_petugas_can_open_data_buku_page(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($petugas)
            ->get('/katalog-admin');

        $response->assertStatus(200);
    }

    public function test_petugas_can_create_buku(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
        ]);

        $response = $this->actingAs($petugas)->post('/buku', [
            'judul' => 'Belajar Laravel',
            'penulis' => 'Budi',
            'penerbit' => 'Gramedia',
            'tahun_terbit' => 2024,
            'stok' => 5,
        ]);

        $response->assertRedirect('/katalog-admin');
        $this->assertDatabaseHas('bukus', ['judul' => 'Belajar Laravel']);
    }
}
