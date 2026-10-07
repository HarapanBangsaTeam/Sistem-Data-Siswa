<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Http\Requests\StoreSiswaRequest;
use App\Http\Requests\UpdateSiswaRequest;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Siswa::query()->with('kelas');

        // Status filter: default tampilkan siswa aktif jika tidak difilter khusus
        $status = $request->input('status', 'aktif');
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->input('kelas_id'));
        }

        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->input('angkatan'));
        }

        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function (Builder $q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nipd', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $siswas = $query->orderBy('nama')->paginate(20)->withQueryString();
        $kelasList = Kelas::orderBy('nama')->get();
        $angkatanList = Siswa::select('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        return view('siswa.index', compact('siswas', 'kelasList', 'angkatanList'));
    }

    public function create(): View
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $defaultAngkatan = now()->year;

        return view('siswa.create', compact('kelasList', 'defaultAngkatan'));
    }

    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $siswa = Siswa::create($validated);

            $this->syncOrangTua($siswa->id, 'ayah', $validated['ayah'] ?? []);
            $this->syncOrangTua($siswa->id, 'ibu', $validated['ibu'] ?? []);
            $this->syncOrangTua($siswa->id, 'wali', $validated['wali'] ?? []);
        });

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load(['kelas', 'ayah', 'ibu', 'wali']);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa): View
    {
        $siswa->load(['ayah', 'ibu', 'wali']);
        $kelasList = Kelas::orderBy('nama')->get();

        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($siswa, $validated) {
            $siswa->update($validated);

            $this->syncOrangTua($siswa->id, 'ayah', $validated['ayah'] ?? []);
            $this->syncOrangTua($siswa->id, 'ibu', $validated['ibu'] ?? []);
            $this->syncOrangTua($siswa->id, 'wali', $validated['wali'] ?? []);
        });

        return redirect()->route('siswa.show', $siswa)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dipindahkan ke sampah (soft delete).');
    }

    public function perKelas(Kelas $kelas): View
    {
        $siswas = Siswa::where('kelas_id', $kelas->id)
            ->aktif()
            ->orderBy('nama')
            ->get();

        $totalL = $siswas->where('jk', 'L')->count();
        $totalP = $siswas->where('jk', 'P')->count();
        $kelasList = Kelas::orderBy('nama')->get();

        return view('siswa.per-kelas', compact('kelas', 'siswas', 'totalL', 'totalP', 'kelasList'));
    }

    public function perAngkatan(int $angkatan): View
    {
        $siswas = Siswa::with('kelas')
            ->where('angkatan', $angkatan)
            ->orderBy('nama')
            ->get();

        $summary = [
            'aktif' => $siswas->where('status', 'aktif')->count(),
            'lulus' => $siswas->where('status', 'lulus')->count(),
            'pindah' => $siswas->where('status', 'pindah')->count(),
            'keluar' => $siswas->where('status', 'keluar')->count(),
            'total' => $siswas->count(),
        ];

        $angkatanList = Siswa::select('angkatan')->distinct()->orderByDesc('angkatan')->pluck('angkatan');

        return view('siswa.per-angkatan', compact('angkatan', 'siswas', 'summary', 'angkatanList'));
    }

    public function lulus(Request $request): View
    {
        $query = Siswa::lulus();

        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_lulus', $request->input('tahun_lulus'));
        }

        $siswas = $query->orderByDesc('tahun_lulus')->orderBy('nama')->paginate(20)->withQueryString();
        $tahunLulusList = Siswa::lulus()->whereNotNull('tahun_lulus')->select('tahun_lulus')->distinct()->orderByDesc('tahun_lulus')->pluck('tahun_lulus');
        $kelasList = Kelas::orderBy('nama')->get();

        return view('siswa.lulus', compact('siswas', 'tahunLulusList', 'kelasList'));
    }

    public function luluskan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_ids' => ['required', 'array', 'min:1'],
            'siswa_ids.*' => ['exists:siswas,id'],
            'tahun_lulus' => ['required', 'integer', 'min:2000', 'max:'.(now()->year + 1)],
        ]);

        Siswa::whereIn('id', $validated['siswa_ids'])->update([
            'status' => 'lulus',
            'tahun_lulus' => $validated['tahun_lulus'],
            'kelas_id' => null,
        ]);

        $count = count($validated['siswa_ids']);

        return back()->with('success', "Sebanyak {$count} siswa berhasil dinyatakan lulus (Tahun {$validated['tahun_lulus']}).");
    }

    public function batalkanLulus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $siswa = Siswa::findOrFail($validated['siswa_id']);
        $siswa->update([
            'status' => 'aktif',
            'tahun_lulus' => null,
            'kelas_id' => $validated['kelas_id'],
        ]);

        return back()->with('success', "Kelulusan siswa {$siswa->nama} berhasil dibatalkan dan dimasukkan ke kelas kembali.");
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'angkatan' => ['required', 'integer', 'min:2000', 'max:'.(now()->year + 1)],
        ]);

        $import = new SiswaImport((int) $request->input('angkatan'));
        Excel::import($import, $request->file('file'));

        $msg = "Import selesai: {$import->successCount} siswa baru ditambahkan, {$import->updatedCount} data diperbarui.";

        if (! empty($import->errors)) {
            return back()->with('warning', $msg.' Terjadi beberapa kendala pada '.count($import->errors).' baris.')->with('import_errors', $import->errors);
        }

        return back()->with('success', $msg);
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filename = 'data-siswa-tk-'.date('Ymd-His').'.xlsx';

        return Excel::download(new SiswaExport($request->all()), $filename);
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:siswas,id'],
        ]);

        $ids = $request->input('ids');
        $count = count($ids);
        Siswa::whereIn('id', $ids)->delete();

        return redirect()->route('siswa.index')->with('success', "{$count} data siswa berhasil dipindahkan ke sampah.");
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncOrangTua(int $siswaId, string $hubungan, array $data): void
    {
        $hasContent = false;
        foreach ($data as $value) {
            if (! empty($value)) {
                $hasContent = true;
                break;
            }
        }

        if (! $hasContent) {
            OrangTua::where('siswa_id', $siswaId)->where('hubungan', $hubungan)->delete();

            return;
        }

        OrangTua::updateOrCreate(
            ['siswa_id' => $siswaId, 'hubungan' => $hubungan],
            $data
        );
    }
}
