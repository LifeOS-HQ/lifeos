<template>

    <widget-base title="Schritte" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter-weeks-count="filter.weeks_count" @updatingAttribute="setAttribute($event)" @updatingWeeksCount="filter.weeks_count = $event; fetch();"></widget-base>

</template>

<script>
    import widgetBase from '../base.vue';

    export default {

        components: {
            widgetBase
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
                component.chartOptions = response.data.chartOptions;
                component.chartOptions.plotOptions.column.events.click = function (event) {
                    component.setAttribute(event.point.series.options.custom.slug);
                };
                component.interval_avgs = response.data.interval_avgs;
                component.setAttribute(Object.keys(component.interval_avgs)[0]);
            },
            setAttribute(slug) {
                this.attribute = this.interval_avgs[slug];
            },
        },

    };
</script>