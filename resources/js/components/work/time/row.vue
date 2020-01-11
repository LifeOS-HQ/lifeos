<template>
    <tr>
        <td>{{ item.date_formatted }}</td>
        <td>{{ item.start_at_formatted }}</td>
        <td>{{ item.end_at_formatted }}</td>
        <td class="text-right">{{ item.seconds_break_formatted }}</td>
        <td class="text-right">{{ item.seconds_formatted }}</td>
        <td class="text-right"></td>
    </tr>
</template>

<script>
    export default {

        components: {

        },

        props: ['item', 'uri'],

        data () {
            return {
                id: this.item.id,
                form: {

                },
                errors: {},
            };
        },

        methods: {

        },
    };
</script>