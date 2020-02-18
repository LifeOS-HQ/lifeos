<template>
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <div class="col">Bewertungen</div>
        </div>
        <div class="card-body">
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

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {
            data() {

                return data;
            },
        },

        data() {

            var chartData = [];

            for (var i = this.model.ratings.length - 1; i >= 0; i--) {
                chartData.push([
                    this.model.ratings[i].review.title,
                    this.model.ratings[i].rating || 0,
                ]);
            }

            return {
                chartOptions: {
                    chart: {
                        type: 'column',
                    },
                    xAxis: {
                        type: 'category',
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        }
                    },
                    yAxis: [
                        {
                            min: 0,
                            title: {
                                text: 'Bewertung'
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
                    title: {
                        text: '',
                    },
                    series: [{
                        name: 'Bewertungen',
                        data: chartData,
                    }],
                },
            };
        },

        mounted() {

        },

        methods: {

        },
    };
</script>