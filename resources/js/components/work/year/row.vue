<template>
    <tr>
        <td class="align-middle">{{ item.year }}</td>
        <td class="align-middle text-right" :class="{'text-danger': !hasWorkedAvailableDays, 'text-success': hasWorkedAvailableDays }">{{ item.days_worked }}/{{ item.available_working_days }}</td>
        <td class="align-middle text-right" :class="{'text-danger': !hasWorkedAvailableHours, 'text-success': hasWorkedAvailableHours }">{{ item.hours_worked_formatted }}</td>
        <td class="align-middle text-right">
            <input class="form-control align-middle text-right" :class="'planned_working_hours_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.planned_working_hours_formatted" @keydown.enter="update(false)">
            <div class="invalid-feedback" v-text="'planned_working_hours_formatted' in errors ? errors.planned_working_hours_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right" :class="{'text-danger': !hasWorkedHoursDays, 'text-success': hasWorkedHoursDays }">{{ item.hours_worked_day_formatted }}/{{ item.planned_working_hours_day_formatted }}</td>
        <td class="align-middle text-right">
            <input class="form-control align-middle text-right" :class="'wage_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.wage_formatted" @keydown.enter="update(false)">
            <div class="invalid-feedback" v-text="'wage_formatted' in errors ? errors.wage_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <input class="form-control align-middle text-right" :class="'wage_bonus_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.wage_bonus_formatted" @keydown.enter="update(false)">
            <div class="invalid-feedback" v-text="'wage_bonus_formatted' in errors ? errors.wage_bonus_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right">{{ item.wage_total_formatted }}</td>
        <td class="align-middle text-right">{{ item.bonus_formatted }}</td>
        <td class="align-middle text-right">{{ item.gross_formatted }}</td>
        <td class="align-middle text-right">
            <input class="form-control align-middle text-right" :class="'tax_refund_formatted' in errors ? 'is-invalid' : ''" type="text" v-model="form.tax_refund_formatted" @keydown.enter="update(false)">
            <div class="invalid-feedback" v-text="'tax_refund_formatted' in errors ? errors.tax_refund_formatted[0] : ''"></div>
        </td>
        <td class="align-middle text-right">{{ item.net_formatted }}</td>
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
                    planned_working_hours_formatted: this.item.planned_working_hours_formatted,
                    tax_refund_formatted: this.item.tax_refund_formatted,
                    wage_bonus_formatted: this.item.wage_bonus_formatted,
                    wage_formatted: this.item.wage_formatted,
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
                this.form.tax_refund_formatted = value.tax_refund_formatted;
                this.form.planned_working_hours_formatted = value.planned_working_hours_formatted;
                this.form.wage_formatted = value.wage_formatted;
                this.form.wage_bonus_formatted = value.wage_bonus_formatted;
            },
        },
    };
</script>