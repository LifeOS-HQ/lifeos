<template>

    <widget-base title="Gewicht" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter="filter" @updatingAttribute="setAttribute($event)" @updatingFilter="updatingFilter($event)">

        <template v-slot:header>

            <table class="table table-fixed table-hover table-striped table-sm bg-white mb-3">
                <tbody>
                    <tr>
                        <td>Ø Gewicht vor 14 - 7 Tage</td>
                        <td class="text-right" width="100">{{ table.last_weight_avg_formatted }} kg</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Ø Gewicht letzte 7 Tage</td>
                        <td class="text-right" width="100">{{ table.current_weight_avg_formatted }} kg</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Differenz</td>
                        <td class="text-right" width="100">{{ table.weight_difference_formatted }} kg</td>
                        <td class="text-muted">Ziel: {{ table.weight_difference_goal_formatted }} kg ({{ table.weight_difference_goal_difference_formatted }} kg)</td>
                    </tr>
                    <tr>
                        <td>Differenz Kalorien pro Tag</td>
                        <td class="text-right" width="100">{{ table.weight_difference_kcal_formatted }} kcal</td>
                        <td class="text-muted">Ziel: {{ table.weight_difference_goal_kcal_formatted }} kcal ({{ table.weight_difference_goal_kcal_difference_formatted }} kcal)</td>
                    </tr>
                    <tr>
                        <td>Ø Kalorien letzte 7 Tage</td>
                        <td class="text-right" width="100">{{ table.energy_avg_goal_formatted }} kcal</td>
                        <td class="text-muted">Ziel: {{ table.energy_avg_goal_formatted }} kcal ({{ table.energy_avg_goal_diference_formatted }} kcal)</td>
                    </tr>
                </tbody>
            </table>

        </template>

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
                indexPath: '/widgets/health/weight',
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