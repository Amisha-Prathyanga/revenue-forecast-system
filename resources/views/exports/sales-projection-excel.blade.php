<table>
    <thead>
        <tr>
            <th>Category</th>
            <th>Sub Products</th>
            @foreach($months as $month)
                <th>{{ $month->format('F') }}</th>
            @endforeach
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        <!-- Revenue Section -->
        <tr>
            <td colspan="{{ count($months) + 3 }}">Revenue</td>
        </tr>
        @foreach($categories as $category)
            @foreach($category->subCategories as $subCategory)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $subCategory->name }}</td>
                    @foreach($months as $month)
                        <td>
                            {{ ($data[$category->id][$subCategory->id][$month->format('n')] ?? collect())->sum('revenue') }}
                        </td>
                    @endforeach
                    <td>
                        {{ $categoryTotals[$category->id][$subCategory->id]['revenue'] ?? 0 }}
                    </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2">Total Revenue</td>
            @foreach($months as $month)
                <td>{{ $monthlyTotals['revenue'][$month->format('n')] }}</td>
            @endforeach
            <td>{{ array_sum($monthlyTotals['revenue']) }}</td>
        </tr>

        <!-- Foreign Costs Section -->
        <tr>
            <td colspan="{{ count($months) + 3 }}">Foreign Costs</td>
        </tr>
        @foreach($categories as $category)
            @foreach($category->subCategories as $subCategory)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $subCategory->name }}</td>
                    @foreach($months as $month)
                        <td>
                            {{ ($data[$category->id][$subCategory->id][$month->format('n')] ?? collect())->sum('foreign_costs') }}
                        </td>
                    @endforeach
                    <td>
                        {{ $categoryTotals[$category->id][$subCategory->id]['foreign_costs'] ?? 0 }}
                    </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2">Total Foreign Costs</td>
            @foreach($months as $month)
                <td>{{ $monthlyTotals['foreign_costs'][$month->format('n')] }}</td>
            @endforeach
            <td>{{ array_sum($monthlyTotals['foreign_costs']) }}</td>
        </tr>

        <!-- Local Costs Section -->
        <tr>
            <td colspan="{{ count($months) + 3 }}">Local Costs</td>
        </tr>
        @foreach($categories as $category)
            @foreach($category->subCategories as $subCategory)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $subCategory->name }}</td>
                    @foreach($months as $month)
                        <td>
                            {{ ($data[$category->id][$subCategory->id][$month->format('n')] ?? collect())->sum('local_costs') }}
                        </td>
                    @endforeach
                    <td>
                        {{ $categoryTotals[$category->id][$subCategory->id]['local_costs'] ?? 0 }}
                    </td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="2">Total Local Costs</td>
            @foreach($months as $month)
                <td>{{ $monthlyTotals['local_costs'][$month->format('n')] }}</td>
            @endforeach
            <td>{{ array_sum($monthlyTotals['local_costs']) }}</td>
        </tr>

        <!-- Gross Revenue -->
        <tr>
            <td colspan="2">Gross Revenue</td>
            @foreach($months as $month)
                <td>{{ $grossRevenue[$month->format('n')] }}</td>
            @endforeach
            <td>{{ array_sum($grossRevenue) }}</td>
        </tr>
    </tbody>
</table>