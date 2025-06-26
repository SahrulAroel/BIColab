<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    // ✅ TAMPILKAN HALAMAN UTAMA
    public function index()
    {
        $data = Pembayaran::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        return view('pages.jenis_pembayaran', compact('data'));
    }

    // ✅ SIMPAN DATA DARI FORM (PAKAI POLI JUGA)
    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'jenis_pembayaran' => 'required',
            'bulan' => 'required|numeric|between:1,12',
            'tahun' => 'required|numeric|min:2020',
            'poli' => 'required|in:Poli Umum,Poli Gigi,Poli Anak,Poli Kandungan,Poli Saraf,Poli Penyakit Dalam'
        ]);

        Pembayaran::create([
            'nama_pasien' => $request->nama_pasien,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jenis_pembayaran' => $request->jenis_pembayaran,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'poli' => $request->poli,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan!');
    }

    // ✅ HALAMAN GRAFIK
    public function grafik()
    {
        $data = DB::table('pembayarans')
            ->select('jenis_pembayaran', 'bulan', 'tahun', DB::raw('count(*) as total'))
            ->groupBy('jenis_pembayaran', 'bulan', 'tahun')
            ->get();

        $summary = [
            'BPJS' => Pembayaran::where('jenis_pembayaran', 'BPJS')->count(),
            'Umum' => Pembayaran::where('jenis_pembayaran', 'Umum')->count(),
            'Asuransi Swasta' => Pembayaran::where('jenis_pembayaran', 'Asuransi Swasta')->count(),
            'Perusahaan' => Pembayaran::where('jenis_pembayaran', 'Perusahaan')->count(),
        ];

        $pieLabels = array_keys($summary);
        $pieData = array_values($summary);

        return view('pages.grafik_pembayaran', compact('data', 'summary', 'pieLabels', 'pieData'));
    }

    // ✅ HAPUS DATA
    public function destroy($id)
    {
        $data = Pembayaran::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
