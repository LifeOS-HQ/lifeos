<template>
    <div>
        <month :years="years"></month>
        <year :years="years"></year>
    </div>
</template>

<script>
    import month from './month.vue';
    import year from './year.vue';

    export default {
        components: {
            month,
            year,
        },

        props: {
            years: {
                type: Object,
                required: true,
            },
        },
    };
</script>