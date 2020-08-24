<template>
    <tr>
        <td class="align-middle">{{ item.year }}</td>
        <td class="align-middle text-right">{{ item.investedCapital.start_formatted }} €</td>
        <td class="align-middle text-right">{{ item.investedCapital.end_formatted }} €</td>
        <td class="align-middle text-right">{{ item.investedCapital.diff_formatted }} €</td>
        <td class="align-middle text-right">{{ item.dividends.net_formatted }} €</td>
        <td class="align-middle text-right">{{ (item.dividends.net[0] / item.investedCapital.end * 100).format(2, ',', '.') }} %</td>
        <td class="align-middle text-right">{{ (item.investedCapital.end / item.dividends.net[0] * 12).format(2, ',', '.') }} €</td>
        <td class="align-middle text-right">{{ (item.investedCapital.diff / (item.wage.net_in_cents / 100) * 100).format(0, ',', '.') }} %</td>
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
    };
</script>