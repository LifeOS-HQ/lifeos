<template>

    <widget-base title="Gewicht" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter="filter" @updatingAttribute="setAttribute($event)" @updatingFilter="updatingFilter($event)">

        <template v-slot:header>

            <div class="text-center mb-3">
                <ul class="list-group list-group-horizontal">
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.current_weight_avg_formatted }} kg</div>
                        <div class="text-muted">Ø Gewicht letzte 7 Tage</div>
                    </li>
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.last_weight_avg_formatted }} kg</div>
                        <div class="text-muted">Ø Gewicht vor 14 - 7 Tage</div>
                    </li>
                </ul>
                <ul class="mt-0 list-group list-group-horizontal">
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.weight_difference_formatted }} kg</div>
                        <div class="text-muted">Differenz</div>
                    </li>
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.weight_difference_goal_formatted }} kg</div>
                        <div class="text-muted">Ziel ({{ table.weight_difference_goal_difference_formatted }} kg)</div>
                    </li>
                </ul>
                <ul class="mt-0 list-group list-group-horizontal">
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.weight_difference_kcal_formatted }} kcal</div>
                        <div class="text-muted">Differenz Kalorien pro Tag</div>
                    </li>
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.weight_difference_goal_kcal_formatted }} kcal</div>
                        <div class="text-muted">Ziel ({{ table.weight_difference_goal_kcal_difference_formatted }} kcal)</div>
                    </li>
                </ul>
                <ul class="mt-0 list-group list-group-horizontal">
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.energy_avg_formatted }} kcal</div>
                        <div class="text-muted">Ø Kalorien letzte 7 Tage</div>
                    </li>
                    <li class="col list-group-item">
                        <div class="font-weight-bolder">{{ table.energy_avg_goal_formatted }} kcal</div>
                        <div class="text-muted">Ziel ({{ table.energy_avg_goal_diference_formatted }} kcal)</div>
                    </li>
                </ul>
            </div>

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