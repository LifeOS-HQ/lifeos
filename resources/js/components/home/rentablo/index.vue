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
            <table class="table table-hover table-striped" v-else>
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-right">Gesamt</th>
                        <th class="text-right" v-for="(account, accountId) in accounts">{{ account.name }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Wert</td>
                        <td class="text-right">{{ valuations[0].format(2, ',', '.') }} €</td>
                        <td class="text-right" v-for="(account, accountId) in accounts">{{ valuations[accountId].format(2, ',', '.') }} €</td>
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
        </div>
    </div>
</template>

<script>
    import {Chart} from 'highcharts-vue'

    export default {

        components: {
            highcharts: Chart
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