<template>
    <tr>
        <td>{{ item.year }}</td>
        <td class="text-right">{{ item.days_worked }}/{{ item.available_working_days }}</td>
        <td class="text-right">{{ item.hours_worked_formatted }}/{{ item.planned_working_hours_formatted }}</td>
        <td class="text-right">{{ item.hours_worked_day_formatted }}/{{ item.planned_working_hours_day_formatted }}</td>
        <td class="text-right">{{ item.wage_formatted }}</td>
        <td class="text-right">{{ item.wage_bonus_formatted }}</td>
        <td class="text-right">{{ item.wage_total_formatted }}</td>
        <td class="text-right">{{ item.bonus_formatted }}</td>
        <td class="text-right">{{ item.gross_formatted }}</td>
        <td class="text-right">{{ item.net_formatted }}</td>
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