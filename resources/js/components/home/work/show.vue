<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <div>Arbeitszeit {{ month_name }}</div>
            <a class="text-body" href="/work"><i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card-body">
            <table class="table table-fixed table-hover table-striped table-sm bg-white">
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
                        <td class="text-right">{{ statistics.workingdays_worked.format(0, ',', '.') }} <span v-if="statistics.workingdays_worked != statistics.days_worked">({{ statistics.days_worked }})</span></td>
                        <td class="text-right">{{ (statistics.available_working_days).format(0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Stunden</td>
                        <td class="text-right" :class="{'text-danger': !hasReachedPlannedHours, 'text-success': hasReachedPlannedHours }">{{ last_day.industryHours_formatted }}</td>
                        <td class="text-right">{{ (statistics.hours_worked).format(2, ',', '.') }} <span v-if="is_current_month">({{ (statistics.available_hours_worked).format(2, ',', '.') }})</span></td>
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
        </div>
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
                is_current_month: false,
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
                    workingdays_worked: 0,
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
                        component.is_current_month = response.data.is_current_month;
                        component.statistics = response.data.statistics;
                        component.last_day = response.data.last_day;
                        component.isLoading = false;
                    });
            },
        },

    };
</script>