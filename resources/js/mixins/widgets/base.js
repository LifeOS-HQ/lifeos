export const baseMixin = {

    data () {
        return {
            chartOptions: {

            },
            isLoading: true,
            interval_avgs: {

            },
            attribute: {

            },
            filter: {
                interval_count: 4,
                interval_unit: 'weeks',
                interval_reference: 'relative',
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
            if ('column' in component.chartOptions.plotOptions) {
                component.chartOptions.plotOptions.column.events.click = function (event) {
                    component.setAttribute(event.point.series.options.custom.slug);
                };
            }
            else if ('pie' in component.chartOptions.plotOptions) {
                component.chartOptions.plotOptions.pie.events.click = function (event) {
                    component.setAttribute(event.point.slug);
                };
            }
            component.interval_avgs = response.data.interval_avgs;
            component.setAttribute(Object.keys(component.interval_avgs)[0]);
        },
        setAttribute(slug) {
            if (slug in this.interval_avgs) {
                this.attribute = this.interval_avgs[slug];
            }
        },
        updatingFilter({key, value}) {
            Vue.set(this.filter, key, value);
            this.fetch();
        }
    },
};