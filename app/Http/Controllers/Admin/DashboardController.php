<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard with summary statistics.
     */
    public function index(): Response
    {
        $statistics = $this->dashboardService->getSummaryStatistics();
        $chartData = $this->dashboardService->getChartData(30);
        $categoryBreakdown = $this->dashboardService->getCategoryBreakdown();
        $formBreakdown = $this->dashboardService->getFormBreakdown();
        $averageProcessingTime = $this->dashboardService->getAverageProcessingTime();

        return Inertia::render('Admin/Dashboard', [
            'statistics' => $statistics,
            'chartData' => $chartData,
            'categoryBreakdown' => $categoryBreakdown,
            'formBreakdown' => $formBreakdown,
            'averageProcessingTime' => $averageProcessingTime,
        ]);
    }

    /**
     * Get filtered statistics data.
     */
    public function getStats(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'days' => 'nullable|integer|min:1|max:365',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $days = $request->input('days', 30);

        $statistics = $this->dashboardService->getSummaryStatistics($startDate, $endDate);
        $chartData = $this->dashboardService->getChartData($days, $startDate, $endDate);
        $categoryBreakdown = $this->dashboardService->getCategoryBreakdown($startDate, $endDate);
        $formBreakdown = $this->dashboardService->getFormBreakdown($startDate, $endDate);
        $averageProcessingTime = $this->dashboardService->getAverageProcessingTime($startDate, $endDate);

        return response()->json([
            'statistics' => $statistics,
            'chartData' => $chartData,
            'categoryBreakdown' => $categoryBreakdown,
            'formBreakdown' => $formBreakdown,
            'averageProcessingTime' => $averageProcessingTime,
        ]);
    }
}
