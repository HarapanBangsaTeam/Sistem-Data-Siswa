<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelasList = Kelas::withCount('siswas')->orderBy('nama')->get();

        return view('kelas.index', compact('kelasList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'tingkat' => ['nullable', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:9'],
            'wali_kelas' => ['nullable', 'string', 'max:100'],
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Rombel / Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:50'],
            'tingkat' => ['nullable', 'string', 'max:10'],
            'tahun_ajaran' => ['required', 'string', 'max:9'],
            'wali_kelas' => ['nullable', 'string', 'max:100'],
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Data Rombel / Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Rombel / Kelas berhasil dihapus.');
    }
}
