<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatistikPenyakit;
use App\Models\KunjunganPasien;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
    $kunjungan = KunjunganPasien::orderBy('bulan')->get();
        

    $kunjungan = DB::table('kunjungan_pasien')
    ->select('bulan', DB::raw('SUM(pasien_baru) as total_baru'), DB::raw('SUM(pasien_lama) as total_lama'))
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->get();

    // Ambil data jumlah pasien tiap penyakit per bulan
    $statistik = StatistikPenyakit::select('jenis_penyakit', 'bulan', DB::raw('SUM(jumlah_pasien) as total'))
        ->groupBy('jenis_penyakit', 'bulan')
        ->orderBy('bulan')
        ->get();

    // Ambil semua bulan unik
    $bulanList = $statistik->pluck('bulan')->unique()->sort()->values();

    // Ambil semua jenis penyakit unik
    $penyakitList = $statistik->pluck('jenis_penyakit')->unique();

    // Format data chart
    $datasets = [];
    foreach ($penyakitList as $penyakit) {
        $dataPerPenyakit = [];
        foreach ($bulanList as $bulan) {
            $found = $statistik->firstWhere(fn($item) => $item->jenis_penyakit === $penyakit && $item->bulan == $bulan);
            $dataPerPenyakit[] = $found ? $found->total : 0;
        }

        $datasets[] = [
            'label' => $penyakit,
            'data' => $dataPerPenyakit,
            'fill' => false,
            'borderColor' => '#' . substr(md5($penyakit), 0, 6),
            'pointRadius' => 3,
            'borderWidth' => 2,
        ];
    }
    $trendData = [];
    foreach ($bulanList as $bulan) {
        $totalPasienBulanIni = $statistik
            ->where('bulan', $bulan)
            ->sum('total');

        $trendData[] = $totalPasienBulanIni;
    }

    // Total jumlah pasien
$totalPasienBaru = KunjunganPasien::sum('pasien_baru');
$totalPasienLama = KunjunganPasien::sum('pasien_lama');
$totalPasien = KunjunganPasien::sum('total_kunjungan');

   $bulan = Carbon::now()->month;
    $tahun = Carbon::now()->year;

    // Ambil top 5 penyakit dengan jumlah pasien terbanyak bulan ini
    $topDiagnoses = StatistikPenyakit::select('jenis_penyakit', DB::raw('SUM(jumlah_pasien) as total'))
        ->where('bulan', $bulan)
        ->where('tahun', $tahun)
        ->groupBy('jenis_penyakit')
        ->orderByDesc('total')
        ->limit(5)
        ->get();



    return view('pages.dashboard', [
        'kunjungan' => $kunjungan,
        'bulanList' => $bulanList,
        'penyakitDatasets' => $datasets,
        'trendData' => $trendData,
        'totalPasienBaru' => $totalPasienBaru,
    'totalPasienLama' => $totalPasienLama,
    'totalPasien' => $totalPasien,
    'topDiagnoses' => $topDiagnoses,
    ]);
}
}