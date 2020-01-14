<template>
    <div class="card">
        <div class="card-header">
            Arbeitszeit {{ month_name }}
        </div>
        <td class="card-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th width="20%"></th>
                        <th class="text-right" width="20%">{{ last_day.date_formatted }}</th>
                        <th class="text-right" width="20%">Ist</th>
                        <th class="text-right" width="20%">Soll</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tage</td>
                        <td class="text-right">-</td>
                        <td class="text-right">{{ statistics.days_worked.format(0, ',', '.') }}</td>
                        <td class="text-right">{{ (statistics.available_working_days).format(0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Stunden</td>
                        <td class="text-right" :class="{'text-danger': !hasReachedPlannedHours, 'text-success': hasReachedPlannedHours }">{{ last_day.industryHours_formatted }}</td>
                        <td class="text-right">{{ (statistics.hours_worked).format(2, ',', '.') }} <span v-if="hasAvailableHoursWorked">({{ (statistics.available_hours_worked).format(2, ',', '.') }})</span></td>
                        <td class="text-right">{{ (statistics.planned_working_hours).format(2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Stunden / Tag</td>
                        <td class="text-right">Ø {{ last_day.industryHours_formatted }}</td>
                        <td class="text-right">Ø {{ (statistics.hours_worked_day).format(2, ',', '.') }}</td>
                        <td class="text-right">Ø {{ (statistics.planned_working_hours_day).format(2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </div>
</template>

<script>
    export default {

        computed: {
            hasAvailableHoursWorked() {
                return (this.statistics.available_hours_worked != this.statistics.hours_worked);
            },
            hasReachedPlannedHours() {
                return (this.last_day.industryHours >= this.statistics.planned_working_hours_day);
            },
        },

        data() {
            return {
                isLoading: false,
                month_name: '',
                last_day: {
                    date_formatted: '',
                    seconds: 0,
                    industryHours: 0,
                },
                statistics: {
                    available_working_days: 0,
                    available_hours_worked: 0,
                    days_worked: 0,
                    hours_worked: 0,
                    hours_worked_day: 0,
                    profit_sum: 0,
                    planned_working_hours: 0,
                    planned_working_hours_day: 0,
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
                axios.get('/home/work')
                    .then( function (response) {
                        component.month_name = response.data.month_name;
                        component.statistics = response.data.statistics;
                        component.last_day = response.data.last_day;
                        component.isLoading = false;
                    });
            },
        },

    };
</script>