<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\ResponseExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function __construct(
        public DashboardService $dashboardService,
        public ResponseExportService $exportService
    ) {}

    /**
     * Tampilkan Dashboard Utama Analitik Admin HESS dengan filter multi-dimensi.
     */
    public function index(Request $request): View
    {
        $data = $this->dashboardService->getDashboardData($request->all());

        return view('admin.dashboard', $data);
    }

    /**
     * Ekspor data hasil respon survei ke format Excel (.xlsx) (mempertahankan filter aktif).
     */
    public function export(Request $request): StreamedResponse
    {
        return $this->exportService->exportExcel($request->all());
    }
}
