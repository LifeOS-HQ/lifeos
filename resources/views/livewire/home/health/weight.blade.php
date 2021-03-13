<div wire:init="loadItems" class="card mt-3">
    <div class="card-header">Gewicht</div>
    <div class="card-body">
        <table class="table table-fixed table-hover table-striped table-sm bg-white mb-3">
            <tbody>
                <tr>
                    <td>Ø Gewicht vor 14 - 7 Tage</td>
                    <td class="text-right" width="100">{{ number_format($last_weight_avg, 2, ',', '.') }} kg</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Ø Gewicht letzte 7 Tage</td>
                    <td class="text-right" width="100">{{ number_format($current_weight_avg, 2, ',', '.') }} kg</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Differenz</td>
                    <td class="text-right" width="100">{{ number_format($weight_difference, 2, ',', '.') }} kg</td>
                    <td class="text-muted">Ziel: {{ number_format($weight_difference_goal, 2, ',' , '.') }} kg ({{ number_format($weight_difference_goal - $weight_difference, 2, ',' , '.') }} kg)</td>
                </tr>
                <tr>
                    <td>Differenz Kalorien pro Tag</td>
                    <td class="text-right" width="100">{{ number_format($weight_difference_kcal, 0, ',', '.') }} kcal</td>
                    <td class="text-muted">Ziel: {{ number_format($weight_difference_goal_kcal, 2, ',', '.') }} kcal ({{ number_format($weight_difference_goal_kcal - $weight_difference_kcal, 0, ',' , '.') }} kcal)</td>
                </tr>
                <tr>
                    <td>Ø Kalorien letzte 7 Tage</td>
                    <td class="text-right" width="100">{{ number_format($energy_avg, 0, ',', '.') }} kcal</td>
                    <td class="text-muted">Ziel: {{ number_format($energy_avg + $weight_difference_goal_kcal, 0, ',', '.') }} kcal</td>
                </tr>
            </tbody>
        </table>

        @isset($items)
            <div id="container"></div>
            <script type="text/javascript">
                var categories = [],
                    series = [];

                <?php foreach($items as $item) { ?>
                    <?php $item->values = $item->values->reverse(); ?>
                    var serie = {
                        name: '<?php echo $item->name; ?>',
                        data: [],
                        yAxis: <?php echo ($item->slug == 'body_fat' ? 1 : 0) ?>,
                        type: '<?php echo ($item->slug == 'body_fat' ? 'column' : 'line') ?>',
                    };
                    categories = [];
                    <?php foreach($item->values as $value) { ?>
                        categories.push('<?php echo $value->at->format('d.m.Y'); ?>');
                        serie.data.push(<?php echo $item->value($value->raw); ?>);
                    <?php } ?>
                    series.push(serie);
                <?php } ?>
                Highcharts.chart('container', {
                    chart: {

                    },
                    title: {
                        text: 'Gewicht im Zeitverlauf'
                    },
                    xAxis: {
                        categories: categories
                    },
                    yAxis: [{
                        title: {
                            text: 'Gewicht (kg)'
                        },
                    }, {
                        title: {
                            text: 'Körperfett (%)'
                        },
                        opposite: true,
                        min: 0.05,
                        max: 0.3,
                    }],
                    plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:,.2f}',
                            },
                        },
                    },
                    tooltip: {
                        headerFormat: '<b>{point.key}</b><br/>',
                        pointFormat: '{series.name}: {point.y:,.2f}'
                    },
                    series: series
                });
            </script>
        @endisset
    </div>
</div>
