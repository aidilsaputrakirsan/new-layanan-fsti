<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Form;
use App\Models\Submission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get summary statistics.
     */
    public function getSummaryStatistics(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Submission::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $totalSubmissions = (clone $query)->count();
        $pendingSubmissions = (clone $query)->where('status', Submission::STATUS_PENDING)->count();
        $inReviewSubmissions = (clone $query)->where('status', Submission::STATUS_IN_REVIEW)->count();
        $needsRevisionSubmissions = (clone $query)->where('status', Submission::STATUS_NEEDS_REVISION)->count();
        $approvedSubmissions = (clone $query)->where('status', Submission::STATUS_APPROVED)->count();
        $rejectedSubmissions = (clone $query)->where('status', Submission::STATUS_REJECTED)->count();
        $completedSubmissions = (clone $query)->where('status', Submission::STATUS_COMPLETED)->count();

        $activeForms = Form::where('is_active', true)->count();
        $activeCategories = Category::where('is_active', true)->count();

        return [
            'total' => $totalSubmissions,
            'pending' => $pendingSubmissions,
            'in_review' => $inReviewSubmissions,
            'needs_revision' => $needsRevisionSubmissions,
            'approved' => $approvedSubmissions,
            'rejected' => $rejectedSubmissions,
            'completed' => $completedSubmissions,
            'active_forms' => $activeForms,
            'active_categories' => $activeCategories,
        ];
    }

    /**
     * Get chart data for submission trends.
     */
    public function getChartData(int $days = 30, ?string $startDate = null, ?string $endDate = null): array
    {
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } else {
            $end = Carbon::now()->endOfDay();
            $start = Carbon::now()->subDays($days - 1)->startOfDay();
        }

        // Get submissions grouped by date
        $submissions = Submission::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
            DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed")
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate all dates in range
        $dates = [];
        $totals = [];
        $pendings = [];
        $completeds = [];

        $current = $start->copy();
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $dates[] = $current->format('d M');
            
            $dayData = $submissions->get($dateStr);
            $totals[] = $dayData ? (int) $dayData->total : 0;
            $pendings[] = $dayData ? (int) $dayData->pending : 0;
            $completeds[] = $dayData ? (int) $dayData->completed : 0;

            $current->addDay();
        }

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Total Pengajuan',
                    'data' => $totals,
                    'borderColor' => '#2080f0',
                    'backgroundColor' => 'rgba(32, 128, 240, 0.1)',
                ],
                [
                    'label' => 'Pending',
                    'data' => $pendings,
                    'borderColor' => '#f0a020',
                    'backgroundColor' => 'rgba(240, 160, 32, 0.1)',
                ],
                [
                    'label' => 'Selesai',
                    'data' => $completeds,
                    'borderColor' => '#18a058',
                    'backgroundColor' => 'rgba(24, 160, 88, 0.1)',
                ],
            ],
        ];
    }


    /**
     * Get breakdown by category.
     */
    public function getCategoryBreakdown(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Category::query()
            ->withCount(['forms as total_forms'])
            ->with(['forms' => function ($q) use ($startDate, $endDate) {
                $q->withCount(['submissions as total_submissions' => function ($sq) use ($startDate, $endDate) {
                    if ($startDate && $endDate) {
                        $sq->whereBetween('created_at', [
                            Carbon::parse($startDate)->startOfDay(),
                            Carbon::parse($endDate)->endOfDay(),
                        ]);
                    }
                }]);
            }]);

        $categories = $query->get();

        return $categories->map(function ($category) {
            $totalSubmissions = $category->forms->sum('total_submissions');
            
            return [
                'id' => $category->id,
                'name' => $category->name,
                'type' => $category->type,
                'total_forms' => $category->total_forms,
                'total_submissions' => $totalSubmissions,
            ];
        })->toArray();
    }

    /**
     * Get breakdown by form.
     */
    public function getFormBreakdown(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Form::query()
            ->with('category:id,name')
            ->withCount(['submissions as total_submissions' => function ($q) use ($startDate, $endDate) {
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay(),
                    ]);
                }
            }])
            ->withCount(['submissions as pending_submissions' => function ($q) use ($startDate, $endDate) {
                $q->where('status', Submission::STATUS_PENDING);
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay(),
                    ]);
                }
            }])
            ->withCount(['submissions as completed_submissions' => function ($q) use ($startDate, $endDate) {
                $q->where('status', Submission::STATUS_COMPLETED);
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay(),
                    ]);
                }
            }])
            ->orderByDesc('total_submissions')
            ->limit(10);

        return $query->get()->map(function ($form) {
            return [
                'id' => $form->id,
                'title' => $form->title,
                'category' => $form->category?->name ?? '-',
                'is_active' => $form->is_active,
                'total_submissions' => $form->total_submissions,
                'pending_submissions' => $form->pending_submissions,
                'completed_submissions' => $form->completed_submissions,
            ];
        })->toArray();
    }

    /**
     * Calculate average processing time (from pending to completed).
     */
    public function getAverageProcessingTime(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Submission::query()
            ->where('status', Submission::STATUS_COMPLETED);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        $completedSubmissions = $query->get();

        if ($completedSubmissions->isEmpty()) {
            return [
                'average_hours' => 0,
                'average_days' => 0,
                'formatted' => '-',
                'total_completed' => 0,
            ];
        }

        $totalHours = 0;
        $count = 0;

        foreach ($completedSubmissions as $submission) {
            $createdAt = Carbon::parse($submission->created_at);
            $updatedAt = Carbon::parse($submission->updated_at);
            $hours = $createdAt->diffInHours($updatedAt);
            $totalHours += $hours;
            $count++;
        }

        $averageHours = $count > 0 ? round($totalHours / $count, 1) : 0;
        $averageDays = round($averageHours / 24, 1);

        // Format the average time
        if ($averageHours < 24) {
            $formatted = $averageHours . ' jam';
        } else {
            $formatted = $averageDays . ' hari';
        }

        return [
            'average_hours' => $averageHours,
            'average_days' => $averageDays,
            'formatted' => $formatted,
            'total_completed' => $count,
        ];
    }
}
