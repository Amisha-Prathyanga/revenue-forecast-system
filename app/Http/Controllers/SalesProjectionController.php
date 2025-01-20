<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\CustomerOpportunity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesProjectionController extends Controller
{
    public function index(Request $request)
    {
        $accountManagers = User::where('usertype', 'accMngr')->get();
        $selectedManager = $request->input('accMngr_id', $accountManagers->first()->id);

        $year = Carbon::now()->year;
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate($year, $i, 1);
        }

        $categories = Category::with('subCategories')->get();
        
        $data = CustomerOpportunity::where('accMngr_id', $selectedManager)
            ->whereYear('date', $year)
            ->get()
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
                    $foreignCosts = $monthlyAmount->sum('foreign_costs');
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
}