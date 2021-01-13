<div wire:init="loadItems" class="card mt-3">
    <div class="card-header">Gewicht</div>
    <div class="card-body">
        <table class="table table-striped mb-3">
            <tbody>
                <tr>
                    <td>Ø Gewicht vor 14 - 7 Tage</td>
                    <td>{{ number_format($last_weight_avg, 2, ',', '.') }} kg</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ø Gewicht letzte 7 Tage</td>
                    <td>{{ number_format($current_weight_avg, 2, ',', '.') }} kg</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Differenz</td>
                    <td>{{ number_format($weight_difference, 2, ',', '.') }} kg</td>
                    <td class="text-muted">Ziel: {{ number_format($weight_difference_goal, 2, ',' , '.') }} kg ({{ number_format($weight_difference_goal - $weight_difference, 2, ',' , '.') }} kg)</td>
                </tr>
                <tr>
                    <td>Differenz Kalorien pro Tag</td>
                    <td>{{ number_format($weight_difference_kcal, 2, ',', '.') }} kcal</td>
                    <td class="text-muted">Ziel: {{ number_format($weight_difference_goal_kcal, 2, ',', '.') }} kcal ({{ number_format($weight_difference_goal_kcal - $weight_difference_kcal, 2, ',' , '.') }} kcal)</td>
                </tr>
                <tr>
                    <td>Ø Kalorien letzte 7 Tage</td>
                    <td>{{ number_format($energy_avg, 2, ',', '.') }} kcal</td>
                    <td class="text-muted">Ziel: {{ number_format($energy_avg + $weight_difference_goal_kcal, 2, ',', '.') }} kcal</td>
                </tr>
            </tbody>
        </table>

        @isset($items)
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        @foreach($items->first()->values as $value)
                            <th class="text-right">
                                {{ $value->at->format('d.m.Y') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            @foreach($item->values as $value)
                                <td class="text-right">{{ $value->raw }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endisset
    </div>
</div>
