<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\CustomerOpportunity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesProjectionExport;
use Maatwebsite\Excel\Facades\Excel;

class SalesProjectionController extends Controller
{
    public function index(Request $request)
    {
        $accountManagers = User::where('usertype', 'accMngr')->get();
        $selectedManager = $request->input('accMngr_id', 'all'); 

        $year = Carbon::now()->year;
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate($year, $i, 1);
        }
    
        $categories = Category::with('subCategories')->where('status', 1)->get();
        
        // Modify the query based on selected manager
        $query = CustomerOpportunity::whereYear('date', $year)->where('status', 1);
        if ($selectedManager !== 'all') {
            $query->where('accMngr_id', $selectedManager);
        }
        
        $data = $query->get()
            ->groupBy([
                'category_id',
                'sub_category_id',
                function ($item) {
                    return Carbon::parse($item->date)->format('n');
                }
            ]);

        
        $monthlyTotals = [];
        $categoryTotals = [];
        foreach ($months as $month) {
            $monthNum = $month->format('n');
            $monthlyTotals['revenue'][$monthNum] = 0;
            $monthlyTotals['foreign_costs'][$monthNum] = 0;
            $monthlyTotals['local_costs'][$monthNum] = 0;
        }

        foreach ($data as $categoryId => $subCategories) {
            foreach ($subCategories as $subCategoryId => $monthlyData) {
                foreach ($months as $month) {
                    $monthNum = $month->format('n');
                    $monthlyAmount = $monthlyData[$monthNum] ?? collect();
                    
                    
                    $revenue = $monthlyAmount->sum('revenue');
                    $foreignCosts = $monthlyAmount->sum('foreign_costs')* 300;
                    $localCosts = $monthlyAmount->sum('local_costs');
                    
                    
                    $monthlyTotals['revenue'][$monthNum] += $revenue;
                    $monthlyTotals['foreign_costs'][$monthNum] += $foreignCosts;
                    $monthlyTotals['local_costs'][$monthNum] += $localCosts;
                    
                    
                    if (!isset($categoryTotals[$categoryId][$subCategoryId])) {
                        $categoryTotals[$categoryId][$subCategoryId] = [
                            'revenue' => 0,
                            'foreign_costs' => 0,
                            'local_costs' => 0
                        ];
                    }
                    $categoryTotals[$categoryId][$subCategoryId]['revenue'] += $revenue;
                    $categoryTotals[$categoryId][$subCategoryId]['foreign_costs'] += $foreignCosts;
                    $categoryTotals[$categoryId][$subCategoryId]['local_costs'] += $localCosts;
                }
            }
        }

        
        $grossRevenue = [];
        foreach ($months as $month) {
            $monthNum = $month->format('n');
            $grossRevenue[$monthNum] = $monthlyTotals['revenue'][$monthNum] - 
                ($monthlyTotals['foreign_costs'][$monthNum] + $monthlyTotals['local_costs'][$monthNum]);
        }

        return view('reports.sales-projection', compact(
            'accountManagers',
            'selectedManager',
            'categories',
            'months',
            'data',
            'monthlyTotals',
            'categoryTotals',
            'grossRevenue'
        ));
    }

    public function export(Request $request)
    {
        $accountManagers = User::where('usertype', 'accMngr')->get();
        $selectedManager = $request->input('accMngr_id', 'all'); 

        $year = Carbon::now()->year;
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate($year, $i, 1);
        }
    
        $categories = Category::with('subCategories')->where('status', 1)->get();
        
        // Modify the query based on selected manager
        $query = CustomerOpportunity::whereYear('date', $year)->where('status', 1);
        if ($selectedManager !== 'all') {
            $query->where('accMngr_id', $selectedManager);
        }
        
        $data = $query->get()
            ->groupBy([
                'category_id',
                'sub_category_id',
                function ($item) {
                    return Carbon::parse($item->date)->format('n');
                }
            ]);

        // Calculate monthly totals
        $monthlyTotals = [];
        $categoryTotals = [];
        foreach ($months as $month) {
            $monthNum = $month->format('n');
            $monthlyTotals['revenue'][$monthNum] = 0;
            $monthlyTotals['foreign_costs'][$monthNum] = 0;
            $monthlyTotals['local_costs'][$monthNum] = 0;
        }

        foreach ($data as $categoryId => $subCategories) {
            foreach ($subCategories as $subCategoryId => $monthlyData) {
                foreach ($months as $month) {
                    $monthNum = $month->format('n');
                    $monthlyAmount = $monthlyData[$monthNum] ?? collect();
                    
                    // Sum up revenue and costs
                    $revenue = $monthlyAmount->sum('revenue');
                    $foreignCosts = $monthlyAmount->sum('foreign_costs')* 300;
                    $localCosts = $monthlyAmount->sum('local_costs');
                    
                    // Add to monthly totals
                    $monthlyTotals['revenue'][$monthNum] += $revenue;
                    $monthlyTotals['foreign_costs'][$monthNum] += $foreignCosts;
                    $monthlyTotals['local_costs'][$monthNum] += $localCosts;
                    
                    // Store category totals
                    if (!isset($categoryTotals[$categoryId][$subCategoryId])) {
                        $categoryTotals[$categoryId][$subCategoryId] = [
                            'revenue' => 0,
                            'foreign_costs' => 0,
                            'local_costs' => 0
                        ];
                    }
                    $categoryTotals[$categoryId][$subCategoryId]['revenue'] += $revenue;
                    $categoryTotals[$categoryId][$subCategoryId]['foreign_costs'] += $foreignCosts;
                    $categoryTotals[$categoryId][$subCategoryId]['local_costs'] += $localCosts;
                }
            }
        }

        // Calculate gross revenue
        $grossRevenue = [];
        foreach ($months as $month) {
            $monthNum = $month->format('n');
            $grossRevenue[$monthNum] = $monthlyTotals['revenue'][$monthNum] - 
                ($monthlyTotals['foreign_costs'][$monthNum] + $monthlyTotals['local_costs'][$monthNum]);
        }
        
        $data = compact(
            'accountManagers',
            'selectedManager',
            'categories',
            'months',
            'data',
            'monthlyTotals',
            'categoryTotals',
            'grossRevenue'
        );

        return Excel::download(
            new SalesProjectionExport($data),
            'sales-projection-' . Carbon::now()->format('Y-m-d') . '.xlsx'
        );
    }
}