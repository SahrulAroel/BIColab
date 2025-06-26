<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        $data = JadwalDokter::all();
        return view('pages.dokter_des', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'spesialis'   => 'required|string|max:100',
            'tanggal'     => 'required|date|after_or_equal:today',
            'status'      => 'required|in:Hadir,Cuti,Tidak Hadir',
        ]);

        JadwalDokter::create([
            'nama_dokter' => $request->nama_dokter,
            'spesialis'   => $request->spesialis,
            'tanggal'     => $request->tanggal,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Jadwal dokter berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = JadwalDokter::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_dokter' => 'required|string|max:100',
            'spesialis'   => 'required|string|max:100',
            'tanggal'     => 'required|date|after_or_equal:today',
            'status'      => 'required|in:Hadir,Cuti,Tidak Hadir',
        ]);

        $data = JadwalDokter::findOrFail($id);
        $data->update([
            'nama_dokter' => $request->nama_dokter,
            'spesialis'   => $request->spesialis,
            'tanggal'     => $request->tanggal,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Jadwal dokter berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = JadwalDokter::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Jadwal dokter berhasil dihapus');
    }
}
