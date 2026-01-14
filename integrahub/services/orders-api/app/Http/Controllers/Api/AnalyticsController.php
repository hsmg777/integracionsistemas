<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AnalyticsController
{
    public function daily(): JsonResponse
    {
        $data = DB::table('analytics_daily')
            ->orderBy('date')
            ->get();

        return response()->json([
            'data' => $data,
        ]);
    }

    public function run(Request $request): JsonResponse
    {
        $date = $request->input('date');

        $command = $date
            ? "analytics:build-daily --date={$date}"
            : "analytics:build-daily";

        Artisan::call($command);

        return response()->json([
            'status' => 'ok',
            'message' => 'Analytics job executed',
            'output' => Artisan::output(),
        ]);
    }

    public function build(Request $request): JsonResponse
{
    $date = $request->input('date');

    if ($date) {
        Artisan::call('analytics:build-daily', [
            '--date' => $date,
        ]);
    } else {
        Artisan::call('analytics:build-daily');
    }

    return response()->json([
        'ok' => true,
        'date' => $date ?? now()->toDateString(),
        'output' => Artisan::output(),
    ]);
}


}
