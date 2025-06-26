<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    // TAMPILKAN HALAMAN UTAMA
    public function index()
    {
        $data = Pembayaran::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        return view('pages.jenis_pembayaran', compact('data'));
    }

    // SIMPAN DATA DARI FORM
    public function store(Request $request)
    {
        $request->validate([
            'jenis_pembayaran' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'bulan' => 'required|numeric|between:1,12',
            'tahun' => 'required|numeric|min:2020',
        ]);

        Pembayaran::create($request->all());

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $data = Pembayaran::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
