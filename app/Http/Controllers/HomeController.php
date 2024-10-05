<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use DB;

use App\Models\Article;
use App\Models\PageView;
use App\Models\Session;

class HomeController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');

        // Browser
        $dataBrowser    =   DB::table('sessions')
                                ->select('browser', DB::raw('count(*) as total'))
                                ->groupBy('browser')
                                ->get();

        $browserName    =   [];
        $browserTotal   =   [];

        if ($dataBrowser->isEmpty()) {
            $browserName[]  =   'Tidak Diketahui';
            $browserTotal[] =   0;
        } else {
            foreach ($dataBrowser as $row) {
                $browserName[]  =   $row->browser;
                $browserTotal[] =   $row->total;
            }
        }

        // Operating System
        $dataPlatform   =   DB::table('sessions')
                                ->select('platform', DB::raw('count(*) as total'))
                                ->groupBy('platform')
                                ->get();

        $platformName    =   [];
        $platformTotal   =   [];

        if ($dataPlatform->isEmpty()) {
            $platformName[]  =   'Tidak Diketahui';
            $platformTotal[] =   0;
        } else {
            foreach ($dataPlatform as $row) {
                $platformName[]  =   $row->platform;
                $platformTotal[] =   $row->total;
            }
        }

        // Visitor
        $currentDate    =   Carbon::now();
        $startDate      =   $currentDate->copy()->subMonths(11)->startOfMonth();
        $visitors       =   DB::table('sessions')
                                ->select(
                                    DB::raw('YEAR(first_active_at) as year'),
                                    DB::raw('MONTH(first_active_at) as month'),
                                    DB::raw('COUNT(session_id) as total')
                                )
                                ->whereBetween('first_active_at', [$startDate, $currentDate])
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'asc')
                                ->orderBy('month', 'asc')
                                ->get();

        $labels         =   [];
        $visitorCounts  =   [];

        for ($i = 0; $i < 12; $i++) {
            $date               =   $startDate->copy()->addMonths($i);
            $labels[]           =   Carbon::parse($date)->translatedFormat('F Y');
            $visitorCounts[]    =   0;
        }

        foreach ($visitors as $visitor) {
            $date   =   Carbon::createFromDate($visitor->year, $visitor->month, 1);
            $label  =   Carbon::parse($date)->translatedFormat('F Y');

            $key    =   array_search($label, $labels);
            if ($key !== false) {
                $visitorCounts[$key]    =   $visitor->total;
            }
        }

        $data['title']          =   'Beranda';
        $data['articles']       =   Article::count();
        $data['pageView']       =   PageView::count();
        $data['visitors']       =   Session::count();
        $data['browserName']    =   $browserName;
        $data['browserTotal']   =   $browserTotal;
        $data['platformName']   =   $platformName;
        $data['platformTotal']  =   $platformTotal;
        $data['visitors']       =   DB::table('sessions')->count();
        $data['visitorLabels']  =   $labels;
        $data['visitorData']    =   $visitorCounts;

        return view('home', $data);
    }
}
