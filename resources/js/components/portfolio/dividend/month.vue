<template>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="col">Dividenden pro Monat</div>
            <div class="col-auto">
                <span style="font-size: 12px;" v-show="isLoading">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
            </div>
            <div class="form-group mb-0">
                <select class="form-control" v-model.number="form.year" @change="fetch">
                    <option :value="year" v-for="(year, index) in years">{{ year }}</option>
                </select>
            </div>
        </div>
        <div class="card-body row">
            <div class="col col-xl-6">
                <highcharts :options="chartOptions"></highcharts>
            </div>
            <div class="col-xl-6 d-none d-xl-block">
                <table class="table table-hover table-striped">
                    <thead>

                    </thead>
                    <tbody>
                        <tr>
                            <td>Dividenden gesamt</td>
                            <td class="text-right">{{ statistics.sum_formatted }}</td>
                        </tr>
                        <tr>
                            <td>Dividenden / Monat</td>
                            <td class="text-right">Ø {{ statistics.avg_per_month_formatted }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-hover table-striped" v-show="Object.keys(investments).length">
                    <thead>
                        <th width="100%">Investment</th>
                        <th class="text-right" width="100">Summe</th>
                        <th class="text-right" width="120">Durchschnitt</th>
                    </thead>
                    <tbody>
                        <tr v-for="(name, investment_id) in investments">
                            <td>{{ name }}</td>
                            <td class="text-right">{{ statistics.sum_per_investment_formatted[investment_id] }}</td>
                            <td class="text-right">Ø {{ statistics.avg_per_investment_formatted[investment_id] }}</td>
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

        computed: {

        },

        data() {
            var date = new Date(),
                start_year = 2015,
                current_year = date.getFullYear(),
                years = [];

            for (var year = start_year; year <= current_year; year++) {
                years.push(year);
            }

            return {
                isLoading: true,
                form: {
                    year: current_year,
                },
                years: years,
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
                                text: 'Euro (€)'
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
                        }
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
                    sum_formatted: '0,00',
                    avg_per_month_formatted: '0,00',
                    sum_per_investment: [],
                },
                investments: {},
            };
        },

        mounted() {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/portfolio/dividend/' + component.form.year)
                    .then( function (response) {
                        component.chartOptions.xAxis.categories = response.data.categories;
                        component.chartOptions.series = response.data.series;
                        component.chartOptions.title = response.data.title;
                        component.statistics = response.data.statistics;
                        component.investments = response.data.investments;
                        component.month_name = response.data.month_name;
                        component.isLoading = false;
                    });
            },
        },
    };
</script>