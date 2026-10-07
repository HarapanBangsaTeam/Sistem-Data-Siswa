<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalAktif = Siswa::aktif()->count();
        $totalLakiLaki = Siswa::aktif()->where('jk', 'L')->count();
        $totalPerempuan = Siswa::aktif()->where('jk', 'P')->count();
        $totalLulus = Siswa::lulus()->count();
        $totalKelas = Kelas::count();

        // Ringkasan per kelas
        $kelasSummary = Kelas::with(['siswas' => function ($query) {
            $query->aktif();
        }])->get()->map(function (Kelas $k) {
            $aktifSiswas = $k->siswas;

            return [
                'id' => $k->id,
                'nama' => $k->nama,
                'tingkat' => $k->tingkat ?? '-',
                'wali_kelas' => $k->wali_kelas ?? '-',
                'tahun_ajaran' => $k->tahun_ajaran,
                'total_siswa' => $aktifSiswas->count(),
                'total_l' => $aktifSiswas->where('jk', 'L')->count(),
                'total_p' => $aktifSiswas->where('jk', 'P')->count(),
            ];
        });

        // Siswa terbaru didaftarkan
        $siswaTerbaru = Siswa::with('kelas')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalAktif',
            'totalLakiLaki',
            'totalPerempuan',
            'totalLulus',
            'totalKelas',
            'kelasSummary',
            'siswaTerbaru'
        ));
    }
}
