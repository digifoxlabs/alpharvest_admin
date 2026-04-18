<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected AdminReportService $reportService
    ) {
    }

    public function index(Request $request): View
    {
        $preset = (string) $request->input('range', 'today');

        return view('admin.reports.index', $this->reportService->build($preset));
    }
}
