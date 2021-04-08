<template>

    <div class="row">

        <div class="col-12 col-lg-6" :key="widget.id" v-for="widget in widgets">
            <component v-bind:is="widget.widget"></component>
        </div>

    </div>

</template>

<script>
    export default {

        components: {

        },

        mixins: [
            //
        ],

        props: {
            view: {
                required: true,
                type: String,
            },
            widgets: {
                required: true,
                type: Array,
            },
        },

        data () {
            return {

            };
        },

        methods: {
            //
        },

    };
</script>