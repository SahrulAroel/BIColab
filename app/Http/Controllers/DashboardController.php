<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatistikPenyakit;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahunDipilih = $request->input('tahun', date('Y'));

        $data = StatistikPenyakit::where('tahun', $tahunDipilih)
            ->get()
            ->groupBy('jenis_penyakit');

    $bulanLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    $datasets = [];
    foreach ($data as $jenis => $items) {
        $bulanData = array_fill(0, 12, 0); // Default 0 untuk semua bulan

        foreach ($items as $item) {
            $index = intval($item->bulan) - 1;
            if ($index >= 0 && $index < 12) {
                $bulanData[$index] += $item->jumlah_pasien;
            }
        }

        // Hapus dataset kalau semua data 0 (tidak ada data)
        if (array_sum($bulanData) > 0 && $jenis != null && $jenis != '') {
            $datasets[] = [
                'label' => $jenis,
                'data' => $bulanData,
                'backgroundColor' => 'rgba(' . rand(50, 200) . ',' . rand(50, 200) . ',' . rand(50, 200) . ',0.7)',
                'borderWidth' => 0,
            ];
        }
    }

    $tahunTersedia = StatistikPenyakit::select('tahun')->distinct()->pluck('tahun');

        return view('pages.dashboard', compact('datasets', 'bulanLabels', 'tahunDipilih', 'tahunTersedia'));
    }
}
