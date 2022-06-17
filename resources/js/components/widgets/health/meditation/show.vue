<template>

    <widget-base title="Meditation" :chartOptions="chartOptions" :interval_avgs="interval_avgs" :attribute="attribute" :is-loading="isLoading" :filter="filter" @updatingAttribute="setAttribute($event)" @updatingFilter="updatingFilter($event)">

    </widget-base>

</template>

<script>
    import widgetBase from '../../base.vue';

    import { baseMixin } from '../../../../mixins/widgets/base.js';

    export default {

        components: {
            widgetBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            //
        },

        data () {
            return {
                indexPath: '/widgets/data/health/meditation',
            };
        },

        methods: {
            //
        },

    };
</script>