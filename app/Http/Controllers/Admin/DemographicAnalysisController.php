<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DemographicAnalysisService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DemographicAnalysisController extends Controller
{
    public function __construct(
        public DemographicAnalysisService $demographicService
    ) {}

    /**
     * Tampilkan halaman khusus Analisis Data Demografi Responden.
     */
    public function index(Request $request): View
    {
        $data = $this->demographicService->getDemographicPageData($request->all());

        return view('admin.demographic-analysis.index', $data);
    }
}
