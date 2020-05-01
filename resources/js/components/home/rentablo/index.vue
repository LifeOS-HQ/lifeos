<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>Rentablo</div>
            <div><i class="fas fa-sync pointer" @click="refresh"></i></div>
        </div>
        <div class="card-body">
            <div v-if="isLoading" class="mt-3 p-5">
                <center>
                    <span style="font-size: 48px;">
                        <i class="fas fa-spinner fa-spin"></i><br />
                    </span>
                    Lade Daten..
                </center>
            </div>
            <template v-else>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-around mx-5 px-5">
                            <div class="col-auto">
                                <h3 class="heading-text text-bold-600">{{ value.currentPortfolioValueFormatted }} €</h3>
                                <div class="text-muted sub-heading">{{ value.currentInvestedCapitalFormatted }} €</div>
                            </div>
                            <div class="col-auto" :class="{'text-danger': !hasPositiveDifference, 'text-success': hasPositiveDifference}">
                                <h3 class="heading-text text-bold-600">{{ value.currentDifferencePercentFormatted }} %</h3>
                                <div class="text-muted">{{ value.currentDifferenceFormatted }} €</div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-right">Gesamt</th>
                            <th class="text-right" v-for="(account, accountId) in accounts">{{ account.name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="align-middle">Wert</td>
                            <td class="align-middle text-right">
                                {{ valuations[0].format(2, ',', '.') }} € <span class="text-muted">(Max: {{ value.maxPortfolioValueFormatted }} €)</span>
                            </td>
                            <td class="align-middle text-right" v-for="(account, accountId) in accounts">{{ valuations[accountId].format(2, ',', '.') }} €</td>
                        </tr>
                        <tr>
                            <td>Dividenden ({{ year }})</td>
                            <td class="text-right">{{ dividends['amount'][0].format(2, ',', '.') }} €</td>
                            <td class="text-right" v-for="(account, accountId) in accounts">{{ dividends['amount'][accountId].format(2, ',', '.') }} €</td>
                        </tr>
                        <tr>
                            <td>Dividenden / Monat ({{ year }})</td>
                            <td class="text-right">Ø {{ dividends['month']['avg'].format(2, ',', '.') }} €</td>
                            <td class="text-right" v-for="(account, accountId) in accounts">Ø {{ (dividends['amount'][accountId] / dividends['month']['count']).format(2, ',', '.') }} €</td>
                        </tr>
                    </tbody>
                </table>
                <highcharts :options="chartOptions"></highcharts>
            </template>
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
            hasPositiveDifference() {
                return (this.value.currentDifference > 0);
            }
        },

        data() {
            return {
                isLoading: true,
                params: {
                    refresh: 0,
                },
                accounts: {},
                dividends: {},
                valuations: {},
                value: {},
                year: '',
                chartOptions: {
                    chart: {
                        type: 'area',
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
                        pointFormat: '{series.name}: {point.y:,.2f} €'
                    },
                    title: {
                        text: '',
                    },
                    series: [],
                },
            };
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/home/rentablo', {
                    params: component.params,
                })
                    .then( function (response) {
                        component.accounts = response.data.accounts;
                        component.dividends = response.data.dividends;
                        component.valuations = response.data.valuations;
                        component.value = response.data.value;
                        component.year = response.data.year;
                        component.chartOptions.xAxis.categories = response.data.chart.categories;
                        component.chartOptions.series = response.data.chart.series;
                        component.chartOptions.title = response.data.chart.title;
                        component.isLoading = false;
                        component.params.refresh = 0;
                    });
            },
            refresh() {
                this.params.refresh = 1;
                this.fetch();
            }
        },

    };
</script>