
@extends('admin.admin_layout')

@section('content') 
 <div class="container-fluid p-4">
        <h2>PROJECTED SALES {{ now()->year }}/{{ now()->year - 1 }}  - MONTHLY BREAKDOWN - TOTAL</h2>
        
        <div class="mb-4">
            <form method="GET" action="{{ route('sales.projection') }}" class="form-inline">
                <div class="form-group">
                    <label for="accMngr_id" class="mr-2">Select Account Manager:</label>
                    <select name="accMngr_id" id="accMngr_id" class="form-control" onchange="this.form.submit()">
                        @foreach($accountManagers as $manager)
                            <option value="{{ $manager->id }}" {{ $selectedManager == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <a href="{{ route('sales.projection.export', ['accMngr_id' => $selectedManager]) }}" 
            class="btn btn-success ml-2 mb-3">
             Export to Excel
         </a>

        <div class="table-responsive">
            <table class="table table-bordered table-fixed table-dark table-striped table-hover">
                <thead>
                    <tr>
                        <th class="table-info">Category</th>
                        <th class="table-info">Sub Products</th>
                        @foreach($months as $month)
                            <th class="table-info">{{ $month->format('F') }}</th>
                        @endforeach
                        <th class="table-info">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Revenue Section -->
                    <tr class="section-header">
                        <td colspan="{{ count($months) + 3 }}">Revenue</td>
                    </tr>
                    @foreach($categories as $category)
                        @foreach($category->subCategories as $subCategory)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $subCategory->name }}</td>
                                @foreach($months as $month)
                                    <td>
                                        {{ number_format(($data[$category->id][$subCategory->id][$month->format('n')] ?? collect())->sum('revenue'), 2) }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ number_format($categoryTotals[$category->id][$subCategory->id]['revenue'] ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr class="total-row">
                        <td class="table-success" colspan="2">Total Revenue</td>
                        @foreach($months as $month)
                            <td>{{ number_format($monthlyTotals['revenue'][$month->format('n')], 2) }}</td>
                        @endforeach
                        <td class="table-success">{{ number_format(array_sum($monthlyTotals['revenue']), 2) }}</td>
                    </tr>

                    <!-- Foreign Costs Section -->
                    <tr class="section-header">
                        <td colspan="{{ count($months) + 3 }}">Foreign Costs</td>
                    </tr>
                    @foreach($categories as $category)
                        @foreach($category->subCategories as $subCategory)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $subCategory->name }}</td>
                                @foreach($months as $month)
                                    <td>
                                        {{ number_format(($data[$category->id][$subCategory->id][$month->format('n')] ?? collect())->sum('foreign_costs'), 2) }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ number_format($categoryTotals[$category->id][$subCategory->id]['foreign_costs'] ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr class="total-row">
                        <td class="table-success" colspan="2">Total Foreign Costs</td>
                        @foreach($months as $month)
                            <td>{{ number_format($monthlyTotals['foreign_costs'][$month->format('n')], 2) }}</td>
                        @endforeach
                        <td class="table-success">{{ number_format(array_sum($monthlyTotals['foreign_costs']), 2) }}</td>
                    </tr>

                    <!-- Local Costs Section -->
                    <tr class="section-header">
                        <td colspan="{{ count($months) + 3 }}">Local Costs</td>
                    </tr>
                    @foreach($categories as $category)
                        @foreach($category->subCategories as $subCategory)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $subCategory->name }}</td>
                                @foreach($months as $month)
                                    <td>
                                        {{ number_format(($data[$category->id][$subCategory->id][$month->format('n')] ?? collect())->sum('local_costs'), 2) }}
                                    </td>
                                @endforeach
                                <td>
                                    {{ number_format($categoryTotals[$category->id][$subCategory->id]['local_costs'] ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    <tr class="total-row">
                        <td class="table-success" colspan="2">Total Local Costs</td>
                        @foreach($months as $month)
                            <td>{{ number_format($monthlyTotals['local_costs'][$month->format('n')], 2) }}</td>
                        @endforeach
                        <td class="table-success">{{ number_format(array_sum($monthlyTotals['local_costs']), 2) }}</td>
                    </tr>

                    <!-- Gross Revenue -->
                    <tr class="total-row">
                        <td class="table-success" colspan="2">Gross Revenue</td>
                        @foreach($months as $month)
                            <td>{{ number_format($grossRevenue[$month->format('n')], 2) }}</td>
                        @endforeach
                        <td class="table-success">{{ number_format(array_sum($grossRevenue), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endsection
    
