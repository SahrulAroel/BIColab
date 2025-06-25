<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatistikPoli;
use Carbon\Carbon;

class Dashboard1Controller extends Controller
{
    public function index(Request $request)
{
    $filter = $request->get('filter', 'bulan'); // default = bulan
    $unit = $request->get('unit'); // filter unit_rs

    $query = StatistikPoli::query();
    if ($unit) {
        $query->where('unit_rs', $unit);
    }

    $data = $query->get();
    $jumlahPasien = $query->sum('jumlah_pasien');

    $labels = [];
    $dataValues = [];

    if ($filter === 'tahun') {
        $grouped = $query->select('tahun', DB::raw('SUM(jumlah_pasien) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $labels = $grouped->pluck('tahun');
        $dataValues = $grouped->pluck('total');
    } else {
        $grouped = $query->select('bulan', DB::raw('SUM(jumlah_pasien) as total'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels = $grouped->pluck('bulan')->map(function ($b) {
            return \Carbon\Carbon::create()->month($b)->translatedFormat('F');
        });

        $dataValues = $grouped->pluck('total');
    }

    $listUnit = [
        'Poli Umum',
        'Poli Anak',
        'Poli Penyakit Dalam',
        'Poli Paru',
        'IGD',
        'Rawat Inap',
    ];

    return view('pages.dashboard1', compact(
        'jumlahPasien',
        'data',
        'labels',
        'dataValues',
        'filter',
        'unit',
        'listUnit'
    ));
}

}
