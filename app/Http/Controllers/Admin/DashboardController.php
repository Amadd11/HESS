<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService
    ) {
    }

    /**
     * Tampilkan Dashboard Utama Analitik Admin HESS dengan filter multi-dimensi.
     */
    public function index(Request $request): View
    {
        $data = $this->dashboardService->getDashboardData($request);

        return view('admin.dashboard', $data);
    }

    /**
     * Ekspor data hasil respon survei ke format CSV (mempertahankan filter aktif).
     */
    public function export(Request $request): StreamedResponse
    {
        return $this->dashboardService->exportCsv($request);
    }
}
