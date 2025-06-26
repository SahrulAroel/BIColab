<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasienPerJenisKelamin;
use Illuminate\Support\Facades\DB;

class PasienPerJenisKelaminController extends Controller
{
    public function index()
    {
        $data = PasienPerJenisKelamin::all();
        $chartData = DB::table('pasien_per_jenis_kelamin')
        ->select('jenis_kelamin', DB::raw('SUM(jumlah_pasien) as total'))
        ->groupBy('jenis_kelamin')
        ->get();

    return view('pages.pasien-jenis-kelamin', [
        'data' => $data,
        'chartData' => $chartData
    ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_kelamin' => 'required',
            'jumlah_pasien' => 'required|numeric',
            'bulan' => 'required',
            'tahun' => 'required',
            'wilayah' => 'nullable',
        ]);

        PasienPerJenisKelamin::create($request->all());

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $data = PasienPerJenisKelamin::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}