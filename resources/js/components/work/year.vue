<template>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="col">Arbeitszeit pro Monat</div>
            <div class="form-group mb-0">
                <select class="form-control" v-model.number="form.year" @change="fetch">
                    <option :value="year.year" v-for="year in years">{{ year.year }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div v-if="isLoading" class="mt-3 p-5">
                <center>
                    <span style="font-size: 48px;">
                        <i class="fas fa-spinner fa-spin"></i><br />
                    </span>
                    Lade Daten..
                </center>
            </div>
            <div class="alert alert-dark mt-3" role="alert" v-else-if="statistics.days_worked == 0">
                Keine Arbeitszeiten im Zeitraum vorhanden.
            </div>
        </div>
        <div class="card-body row">
            <div class="col col-xl-6">
                <highcharts :options="chartOptions" v-if="statistics.days_worked > 0"></highcharts>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <table class="table table-hover table-striped" v-if="statistics.days_worked > 0">
                    <thead>
                        <tr>
                            <th width="20%">Arbeitszeit</th>
                            <th class="text-right" width="20%">Ist</th>
                            <th class="text-right" width="20%">Soll</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tage</td>
                            <td class="text-right">{{ statistics.days_worked.format(0, ',', '.') }}</td>
                            <td class="text-right">{{ (statistics.available_working_days).format(0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Stunden</td>
                            <td class="text-right">{{ (statistics.hours_worked).format(2, ',', '.') }} <span v-if="hasAvailableHoursWorked">({{ (statistics.available_hours_worked).format(2, ',', '.') }})</span></td>
                            <td class="text-right">{{ (statistics.planned_working_hours).format(2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Stunden / Tag</td>
                            <td class="text-right">Ø {{ (statistics.hours_worked_day).format(2, ',', '.') }}</td>
                            <td class="text-right">Ø {{ (statistics.planned_working_hours_day).format(2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-hover table-striped" v-if="statistics.days_worked > 0">
                    <thead>
                        <tr>
                            <th width="20%">Gehalt</th>
                            <th class="text-right" width="20%">Brutto</th>
                            <th class="text-right" width="20%">Netto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gehalt</td>
                            <td class="text-right">{{ statistics.gross }}</td>
                            <td class="text-right">{{ statistics.net }}</td>
                        </tr>
                        <tr>
                            <td>Gehalt / Monat</td>
                            <td class="text-right">{{ statistics.gross_month }}</td>
                            <td class="text-right">Ø {{ statistics.net_month }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import {Chart} from 'highcharts-vue'

    export default {

        components: {
            highcharts: Chart
        },

        props: {
            years: {
                type: Array,
                required: true,
            },
        },

        computed: {
            hasAvailableHoursWorked() {
                return (this.statistics.available_hours_worked != this.statistics.hours_worked);
            },
        },

        data() {
            var date = new Date();

            return {
                isLoading: true,
                form: {
                    year: this.years[0].year,
                },
                month_name: '',
                chartOptions: {
                    chart: {
                        type: 'column',
                    },
                    xAxis: {
                        categories: [],
                    },
                    yAxis: [
                        {
                            min: 0,
                            title: {
                                text: 'Stunden (h)'
                            },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                },
                                formatter: function () {
                                    return (this.total ? Highcharts.numberFormat(this.total, 2) :  '');
                                },
                            },
                        },
                        {
                            allowDecimals: true,
                            min: 0,
                            title: {
                                text: 'Bruttolohn',
                            },
                            opposite: true,
                        },
                    ],
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:,.2f}',
                            },
                        }
                    },
                    tooltip: {
                        headerFormat: '<b>{point.key}</b><br/>',
                        pointFormat: '{series.name}: {point.y:,.2f} €<br/>Total: {point.stackTotal:,.2f} €'
                    },
                    title: {
                        text: '',
                    },
                    series: [],
                },
                statistics: {
                    available_working_days: 0,
                    available_hours_worked: 0,
                    days_worked: 0,
                    hours_worked: 0,
                    hours_worked_day: 0,
                    profit_sum: 0,
                    planned_working_hours: 0,
                    planned_working_hours_day: 0,
                },
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/work/year/' + component.form.year)
                    .then( function (response) {
                        component.chartOptions.xAxis.categories = response.data.categories;
                        component.chartOptions.series = response.data.series;
                        component.chartOptions.title = response.data.title;
                        component.statistics = response.data.statistics;
                        component.month_name = response.data.month_name;
                        component.isLoading = false;
                    });
            },
        },
    };
</script>