<template>
    <tr>
        <td class="align-middle">{{ item.date_formatted }}</td>
        <td class="align-middle text-right" :class="{'text-danger': !hasWorkedAvailableDays, 'text-success': hasWorkedAvailableDays }">{{ item.days_worked }}/{{ item.available_working_days }}</td>
        <td class="align-middle text-right" :class="{'text-danger': !hasWorkedAvailableHours, 'text-success': hasWorkedAvailableHours }">{{ item.hours_worked_formatted }}/{{ item.planned_working_hours_formatted }}</td>
        <td class="align-middle text-right" :class="{'text-danger': !hasWorkedHoursDays, 'text-success': hasWorkedHoursDays }">{{ item.hours_worked_day_formatted }}/{{ item.planned_working_hours_day_formatted }}</td>
        <td class="align-middle text-right">{{ item.wage_formatted }}</td>
        <td class="align-middle text-right">{{ item.wage_bonus_formatted }}</td>
        <td class="align-middle text-right">
            <input class="form-control form-control-sm align-middle text-right" :class="'bonus_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.bonus_formatted" @keydown.enter="update(false)">
            <div class="invalid-feedback" v-text="'bonus_formatted' in errors ? errors.bonus_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right">{{ item.gross_formatted }}</td>
        <td class="align-middle text-right">
            <input class="form-control form-control-sm align-middle text-right" :class="'net_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.net_formatted" @keydown.enter="update(false)">
            <div class="invalid-feedback" v-text="'net_formatted' in errors ? errors.net_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        components: {

        },

        props: ['item', 'uri'],

        computed: {
            hasWorkedAvailableDays() {
                return (this.item.days_worked >= this.item.available_working_days);
            },
            hasWorkedAvailableHours() {
                return (this.item.hours_worked >= this.item.planned_working_hours);
            },
            hasWorkedHoursDays() {
                return (this.item.hours_worked_day >= this.item.planned_working_hours_day);
            },
        },

        data () {
            return {
                id: this.item.id,
                form: {
                    bonus_formatted: this.item.bonus_formatted,
                    net_formatted: this.item.net_formatted,
                },
                errors: {},
            };
        },

        methods: {
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        Vue.success('Datensatz gespeichert.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht gespeichert werden.');
                });
            },
        },

        watch: {
            item(value) {
                this.form.bonus_formatted = value.bonus_formatted;
                this.form.net_formatted = value.net_formatted;
            },
        },
    };
</script>