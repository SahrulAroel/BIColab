<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StatistikPenyakit;
use App\Models\JadwalDokter;
use Carbon\Carbon;

class Dashbord22Controller extends Controller
{
    public function index(Request $request)
    {
        // =====================================
        // DATA PENYAKIT (opsional digunakan)
        // =====================================
        $data = StatistikPenyakit::all();

        $grouped = StatistikPenyakit::select('jenis_penyakit', 'bulan', DB::raw('SUM(jumlah_pasien) as total'))
            ->groupBy('jenis_penyakit', 'bulan')
            ->get();

        $chartData = [];
        foreach ($grouped->groupBy('jenis_penyakit') as $penyakit => $records) {
            $monthly = array_fill(1, 12, 0);
            foreach ($records as $rec) {
                $monthly[(int) $rec->bulan] = $rec->total;
            }

            if (array_sum($monthly) > 0) {
                $color = '#' . substr(md5($penyakit), 0, 6);
                $chartData[] = [
                    'label' => $penyakit,
                    'data' => array_values($monthly),
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'borderWidth' => 1,
                ];
            }
        }

        // =====================================
        // HANDLE PERIODE GRAFIK
        // =====================================
        $periode = $request->get('periode', '7days');
        $rangeTanggal = collect();
        $periodeLabel = '';

        if ($periode === 'today') {
            $rangeTanggal->push(Carbon::today()->toDateString());
            $periodeLabel = 'Hari Ini';
        } elseif ($periode === 'yesterday') {
            $rangeTanggal->push(Carbon::yesterday()->toDateString());
            $periodeLabel = 'Kemarin';
        } elseif ($periode === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $rangeTanggal->push(Carbon::today()->subDays($i)->toDateString());
            }
            $periodeLabel = '30 Hari Terakhir';
        } else { // default 7days
            for ($i = 6; $i >= 0; $i--) {
                $rangeTanggal->push(Carbon::today()->subDays($i)->toDateString());
            }
            $periodeLabel = '7 Hari Terakhir';
        }

        // =====================================
        // STATISTIK TOTAL KEHADIRAN (filtered)
        // =====================================
        $startDate = $rangeTanggal->first();
        $endDate   = $rangeTanggal->last();

        $jadwalSemua = JadwalDokter::whereBetween('tanggal', [$startDate, $endDate])->get();
        $hadir       = $jadwalSemua->where('status', 'Hadir')->count();
        $cuti        = $jadwalSemua->where('status', 'Cuti')->count();
        $tidakHadir  = $jadwalSemua->where('status', 'Tidak Hadir')->count();

        // =====================================
        // JADWAL DOKTER HARI INI (untuk tabel)
        // =====================================
        $hariIni = Carbon::now()->translatedFormat('l');
        $tanggalHariIni = Carbon::today()->toDateString();
        $jadwalHariIni = JadwalDokter::whereDate('tanggal', $tanggalHariIni)->get();

        // =====================================
        // GRAFIK TREN HARIAN
        // =====================================
        $labels = [];
        $hadirPerHari = [];
        $cutiPerHari = [];
        $tidakHadirPerHari = [];

        foreach ($rangeTanggal as $tanggal) {
            $carbon = Carbon::parse($tanggal);
            $labels[] = $carbon->format('d M');

            $jadwal = JadwalDokter::whereDate('tanggal', $tanggal)->get();
            $hadirPerHari[] = $jadwal->where('status', 'Hadir')->count();
            $cutiPerHari[] = $jadwal->where('status', 'Cuti')->count();
            $tidakHadirPerHari[] = $jadwal->where('status', 'Tidak Hadir')->count();
        }

        return view('pages.dashboard_des', compact(
            'data',
            'chartData',
            'jadwalHariIni',
            'hariIni',
            'hadir',
            'cuti',
            'tidakHadir',
            'labels',
            'hadirPerHari',
            'cutiPerHari',
            'tidakHadirPerHari',
            'periode',
            'periodeLabel'
        ));
    }
}
