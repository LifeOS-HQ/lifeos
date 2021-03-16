<template>

    <widget-base title="Schlaf" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter-interval-count="filter.weeks_count" @updatingAttribute="setAttribute($event)" @updatingIntervalCount="filter.weeks_count = $event; fetch();">

        <table class="table table-fixed table-hover table-striped table-sm bg-white">

            <tbody>
                <tr>
                    <td>Ø Zeit im Bett</td>
                    <td>{{ table.time_in_bed_avg_formatted }} h</td>
                </tr>
                <tr>
                    <td>Ø Schlaf</td>
                    <td>{{ table.sleep_avg_formatted }} h</td>
                </tr>
                <tr>
                    <td>Ø Eingeschlafen</td>
                    <td>{{ table.sleep_start_avg_formatted }} Uhr</td>
                </tr>
                <tr>
                    <td>Ø Aufgewacht</td>
                    <td>{{ table.sleep_end_avg_formatted }} Uhr</td>
                </tr>
            </tbody>
        </table>

    </widget-base>

</template>

<script>
    import widgetBase from '../base.vue';

    import { baseMixin } from '../../../mixins/widgets/base.js';

    export default {

        components: {
            widgetBase
        },

        mixins: [
            baseMixin,
        ],

        props: {
            //
        },

        data () {
            return {
                indexPath: '/widgets/health/sleep',
                table: {

                },
            };
        },

        mounted () {
            this.fetch();
        },

        methods: {
            fetched(response) {
                var component = this;
                component.chartOptions = response.data.chartOptions;
                component.chartOptions.plotOptions.column.events.click = function (event) {
                    component.setAttribute(event.point.series.options.custom.slug);
                };
                component.interval_avgs = response.data.interval_avgs;
                component.table = response.data.table;

                component.setAttribute(Object.keys(component.interval_avgs)[0]);
            },
        },

    };
</script>