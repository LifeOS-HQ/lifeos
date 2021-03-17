<template>

    <widget-base title="Schlaf" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter="filter" @updatingAttribute="setAttribute($event)" @updatingFilter="updatingFilter($event)">

        <template v-slot:header>

            <div class="text-center mb-3">
                    <ul class="list-group list-group-horizontal">
                        <li class="col list-group-item">
                            <div class="font-weight-bolder">{{ table.sleep_avg_formatted }} h</div>
                            <div class="text-muted">Ø Schlaf</div>
                        </li>
                        <li class="col list-group-item">
                            <div class="font-weight-bolder">{{ table.time_in_bed_avg_formatted }} h</div>
                            <div class="text-muted">Ø Zeit im Bett</div>
                        </li>
                    </ul>
                    <ul class="mt-0 list-group list-group-horizontal">
                        <li class="col list-group-item">
                            <div class="font-weight-bolder">{{ table.sleep_start_avg_formatted }} Uhr</div>
                            <div class="text-muted">Ø Eingeschlafen</div>
                        </li>
                        <li class="col list-group-item">
                            <div class="font-weight-bolder">{{ table.sleep_end_avg_formatted }} Uhr</div>
                            <div class="text-muted">Ø Aufgewacht</div>
                        </li>
                    </ul>
            </div>

        </template>

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