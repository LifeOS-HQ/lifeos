<template>

    <widget-base title="Gewichtsentwicklung" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter="filter" @updatingAttribute="setAttribute($event)" @updatingFilter="updatingFilter($event)">

        <template v-slot:body>

            <table class="table table-fixed table-hover table-striped table-sm bg-white">
                <thead>
                    <tr>
                        <th width="125">Zeitraum bis</th>
                        <th class="text-right" width="30"></th>
                        <th class="text-center" colspan="2" width="150">Ø Gewicht [kg]</th>
                        <th class="text-center" colspan="2">Ø Ernährung [kcal]</th>
                        <th class="text-right" width="30"></th>
                        <th class="text-center" colspan="2">Ø Aktivität [kcal]</th>
                        <th class="text-right" width="30"></th>
                        <th width="125">Ø Saldo [kcal]</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(interval, interval_index) in table.tbody">
                        <td>{{ interval.date_formatted }}</td>
                        <td class="text-center" v-html="interval.weight_icon"></td>
                        <td class="text-right" width="75">{{ interval.weight_avg_formatted }}</td>
                        <td class="text-right" width="75">{{ interval.weight_difference_absolute_formatted }}</td>

                        <td class="text-right">{{ interval.energy_avg_formatted }} kcal</td>
                        <td class="text-right">{{ interval.energy_difference_absolute_formatted }}</td>
                        <td class="text-center" v-html="interval.energy_icon"></td>

                        <td class="text-right">{{ interval.active_energy_avg_formatted }}</td>
                        <td class="text-right">{{ interval.active_energy_difference_absolute_formatted }}</td>
                        <td class="text-center" v-html="interval.active_energy_icon"></td>

                        <td class="text-right">{{ interval.energy_balance_avg_formatted }}</td>
                    </tr>
                </tbody>
            </table>

        </template>

    </widget-base>

</template>

<script>
    import widgetBase from '../../base.vue';

    import { baseMixin } from '../../../../mixins/widgets/base.js';

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
                indexPath: '/widgets/data/health/weight-development',
                table: {

                },
            };
        },

        methods: {
            fetched(response) {
                var component = this;
                component.table = response.data.table;
            },
        },

    };
</script>