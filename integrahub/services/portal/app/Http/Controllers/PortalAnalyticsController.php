<?php

namespace App\Http\Controllers;

use App\Repositories\AnalyticsRepository;
use Illuminate\Http\Request;

class PortalAnalyticsController
{
    public function index(AnalyticsRepository $repo)
    {
        return view('analytics.dashboard', [
            'daily' => $repo->daily(),
            'live'  => $repo->live(),
        ]);
    }

    public function rebuild(Request $request, AnalyticsRepository $repo)
    {
        $repo->rebuild($request->input('date'));

        return redirect()
            ->back()
            ->with('success', 'Analytics ETL ejecutado correctamente');
    }
}
