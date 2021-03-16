<template>

    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="col">Schritte</div>
            <div class="col-auto">
                <select class="form-control form-control-sm" v-model="filter.weeks_count" @change="fetch">
                    <option v-for="n in 10" :value="n">{{ n }} Wochen</option>
                </select>
            </div>
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

            <div v-else>

                <highcharts :options="chartOptions"></highcharts>

                <div class="row mb-1">
                    <div class="col-auto">
                        <button class="btn btn-sm mr-1" :class="(slug == attribute.slug ? 'btn-primary' : 'btn-secondary')" v-for="(interval, slug) in interval_avgs" @click="setAttribute(slug)">{{ interval.name }}</button>
                    </div>
                </div>

                <table class="table table-fixed table-hover table-striped table-sm bg-white">
                    <thead>
                        <tr>
                            <th width="30"></th>
                            <th>Zeitraum bis</th>
                            <th class="text-right">Ø {{ attribute.name}}</th>
                            <th class="text-right">Differenz</th>
                            <th class="text-right">Prozent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(interval, interval_index) in attribute.intervals">
                            <td><i class="fas" :class="[interval.icon_class, interval.font_color_class]" v-if="interval_index != (attribute.intervals.length - 1)"></i></td>
                            <td>{{ interval.date_formatted }}</td>
                            <td class="text-right">{{ interval.avg_formatted }}</td>
                            <td class="text-right" :class="interval.font_color_class">{{ interval_index == (attribute.intervals.length - 1) ? '' : interval.difference_absolute_formatted }}</td>
                            <td class="text-right" :class="interval.font_color_class">{{ interval_index == (attribute.intervals.length - 1) ? '' : interval.difference_percentage_formatted + ' %' }}</td>
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

        mixins: [
            //
        ],

        props: {
            //
        },

        data () {
            return {
                chartOptions: {

                },
                isLoading: true,
                interval_avgs: {

                },
                attribute: {

                },
                indexPath: '/widgets/health/steps',
                filter: {
                    weeks_count: 4,
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
                axios.get(component.indexPath, {
                    params: component.filter,
                })
                    .then( function (response) {
                        component.fetched(response)

                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                        Vue.error('Daten konnten nicht geladen werden.');
                    });
            },
            fetched(response) {
                var component = this;
                this.chartOptions = response.data.chartOptions;
                this.chartOptions.plotOptions.column.events.click = function (event) {
                    component.setAttribute(event.point.series.options.custom.slug);
                };
                this.interval_avgs = response.data.interval_avgs;
                this.setAttribute(Object.keys(this.interval_avgs)[0]);
            },
            setAttribute(slug) {
                this.attribute = this.interval_avgs[slug];
            },
        },

    };
</script>