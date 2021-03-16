<template>

    <widget-base title="Schritte" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter-interval-count="filter.weeks_count" @updatingAttribute="setAttribute($event)" @updatingIntervalCount="filter.weeks_count = $event; fetch();">

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
                indexPath: '/widgets/health/steps',
            };
        },

        methods: {
            //
        },

    };
</script>