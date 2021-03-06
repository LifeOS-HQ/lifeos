<template>
    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="col">Arbeitszeit pro Tag</div>
            <div class="form-group mr-1 mb-0">
                <select class="form-control form-control-sm" v-model="form.month" @change="fetch">
                    <option value="1">Januar</option>
                    <option value="2">Februar</option>
                    <option value="3">März</option>
                    <option value="4">April</option>
                    <option value="5">Mai</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Dezember</option>
                </select>
            </div>
            <div class="form-group mb-0">
                <select class="form-control form-control-sm" v-model="form.year" @change="fetch">
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
                Keine Arbeitszeit im {{ month_name }} vorhanden.
            </div>
        </div>
        <div class="card-body row">
            <div class="col col-xl-8">
                <highcharts :options="chartOptions" v-if="statistics.days_worked > 0"></highcharts>
            </div>
            <div class="col-xl-4 d-none d-xl-block">
                <table class="table table-fixed table-hover table-striped table-sm bg-white" v-if="statistics.days_worked > 0">
                    <thead>
                        <tr>
                            <th width="100"></th>
                            <th class="text-right">Ist</th>
                            <th class="text-right">Soll</th>
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
                            <td class="text-right">{{ (statistics.hours_worked).format(2, ',', '.') }} <span v-if="is_current_month">({{ (statistics.available_hours_worked).format(2, ',', '.') }})</span></td>
                            <td class="text-right">{{ (statistics.planned_working_hours).format(2, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Stunden / Tag</td>
                            <td class="text-right">Ø {{ (statistics.hours_worked_day).format(2, ',', '.') }}</td>
                            <td class="text-right">Ø {{ (statistics.planned_working_hours_day).format(2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-fixed table-hover table-striped table-sm bg-white" v-if="statistics.days_worked > 0">
                    <thead>
                        <tr>
                            <th width="100">Gehalt</th>
                            <th class="text-right">Brutto</th>
                            <th class="text-right">Netto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gehalt</td>
                            <td class="text-right">{{ statistics.gross }}</td>
                            <td class="text-right">{{ statistics.net }}</td>
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
                    month: date.getMonth() + 1,
                    year: this.years[0].year,
                },
                month_name: '',
                is_current_month: false,
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
                        },
                    ],
                    plotOptions: {
                        column: {
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:,.2f}',
                            },
                        }
                    },
                    tooltip: {
                        headerFormat: '<b>{point.key}</b><br/>',
                        pointFormat: '{series.name}: {point.y:,.2f} h'
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
                axios.get('/work/month/' + component.form.year + '/' + component.form.month)
                    .then( function (response) {
                        component.chartOptions.xAxis.categories = response.data.categories;
                        component.chartOptions.series = response.data.series;
                        component.chartOptions.title = response.data.title;
                        component.statistics = response.data.statistics;
                        component.month_name = response.data.month_name;
                        component.is_current_month = response.data.is_current_month;
                        component.isLoading = false;
                    });
            },
        },
    };
</script>