<template>

    <widget-base title="Kalorien" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter="filter" @updatingAttribute="setAttribute($event)" @updatingFilter="updatingFilter($event)">

        <template v-slot:header>

            <highcharts :options="makros_chart"></highcharts>

        </template>

    </widget-base>

</template>

<script>
    import widgetBase from '../../base.vue';
    import {Chart} from 'highcharts-vue'

    import { baseMixin } from '../../../../mixins/widgets/base.js';

    export default {

        components: {
            highcharts: Chart,
            widgetBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            //
        },

        data () {
            return {
                indexPath: '/widgets/data/health/calories',
                makros_chart: {

                },
            };
        },

        methods: {
            fetched(response) {
                var component = this;
                component.chartOptions = response.data.chartOptions;
                component.chartOptions.plotOptions.column.events.click = function (event) {
                    component.setAttribute(event.point.series.options.custom.slug);
                };
                component.interval_avgs = response.data.interval_avgs;
                component.makros_chart = response.data.makros_chart;

                component.setAttribute(Object.keys(component.interval_avgs)[0]);
            },
        },

    };
</script>