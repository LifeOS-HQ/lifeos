<template>
    <div>
        <month></month>
        <year></year>
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
    };
</script>