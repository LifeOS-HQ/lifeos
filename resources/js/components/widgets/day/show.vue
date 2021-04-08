<template>

    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="col pl-0">
                {{ group.name }}
            </div>
            <div class="d-flex">
                <div class="col-auto px-0 ml-1">
                    <select class="form-control form-control-sm" v-model="filter.interval_count" @change="fetch">
                        <option v-for="n in 12" :value="n">{{ n }}</option>
                    </select>
                </div>
                <div class="col-auto px-0 ml-1">
                    <select class="form-control form-control-sm" v-model="filter.interval_unit" @change="fetch">
                        <option v-for="(name, slug) in intervalUnits" :value="slug">{{ name }}</option>
                    </select>
                </div>
                <div class="col-auto px-0 ml-1" v-if="false" @change="fetch">
                    <select class="form-control form-control-sm" v-model="filter.interval_reference">
                        <option value="absolute">Absolut</option>
                        <option value="relative">Relativ</option>
                    </select>
                </div>
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

                    <highcharts :options="avg_chart"></highcharts>

                    <highcharts :options="attribute.chart_options" :key="attribute.id" v-for="attribute in attributes"></highcharts>

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
            dateString: {
                requred: true,
                type: String,
            },
            group: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                chartOptions: {

                },
                isLoading: true,
                interval_avgs: {

                },
                attributes: [],
                avg_chart: {},
                filter: {
                    interval_count: 4,
                    interval_unit: 'weeks',
                    interval_reference: 'relative',
                },
                intervalUnits: {
                    days: 'Tage',
                    weeks: 'Wochen',
                    months: 'Monate',
                    years: 'Jahre',
                },
            };
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get('/data/day/' + component.dateString + '/' + component.group.id, {
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
                this.attributes = response.data.attributes;
                this.avg_chart = response.data.avg_chart;
            },
        },

        mounted() {
            this.fetch();
        },

    };
</script>