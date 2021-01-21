<div wire:init="loadItems" class="card mt-3">
    <div class="card-header">Kalorien</div>
    <div class="card-body">
        @isset($nutrients)
            <div id="nutrients_pie_chart"></div>
            <script type="text/javascript">
                var data = [],
                    serie = {};

                <?php foreach($nutrients as $item) { ?>
                    data.push({
                        name: '<?php echo $item->name; ?>',
                        y: <?php echo $item->calories_avg; ?>,
                        grams: <?php echo $item->values_avg; ?>
                    });
                <?php } ?>
                serie = {
                    name: 'Makros',
                    data: data,
                };
                Highcharts.chart('nutrients_pie_chart', {
                    chart: {
                        type: 'pie',
                    },
                    title: {
                        text: 'Makros'
                    },
                    yAxis: [{
                        title: {
                            text: 'Kalorien (kcal)'
                        },
                    }],
                    series: [
                        serie
                    ],
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                            }
                        }
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.grams:.1f}g</b>'
                    },
                });
            </script>
        @endisset
        @isset($energy)
            <div id="energy_line_chart"></div>
            <script type="text/javascript">
                var categories = [],
                    series = [];

                <?php foreach($energy as $item) { ?>
                    <?php $item->values = $item->values->reverse(); ?>
                    var serie = {
                        name: '<?php echo $item->name; ?>',
                        data: [],
                        type: 'column',
                    };
                    categories = [];
                    <?php foreach($item->values as $value) { ?>
                        categories.push('<?php echo $value->at->format('d.m.Y'); ?>');
                        serie.data.push(<?php echo $item->value($value->raw); ?>);
                    <?php } ?>
                    series.push(serie);
                <?php } ?>
                Highcharts.chart('energy_line_chart', {
                    chart: {

                    },
                    title: {
                        text: 'Kalorien im Zeitverlauf'
                    },
                    xAxis: {
                        categories: categories
                    },
                    yAxis: [{
                        title: {
                            text: 'Kalorien (kcal)'
                        },
                        plotLines: [{
                            color: '#7cb5ec',
                            value: <?php echo $energy[0]->values_avg; ?>, // Insert your average here
                            width: '1',
                            zIndex: 4 // To not get stuck below the regular plot lines or series
                        }, {
                            color: '#434348',
                            value: <?php echo $energy[1]->values_avg; ?>, // Insert your average here
                            width: '1',
                            zIndex: 4 // To not get stuck below the regular plot lines or series
                        }]
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
