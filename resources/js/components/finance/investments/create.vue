<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <div>Anteile erhöhen</div>
            <div></div>
        </div>
        <div class="card-body">
            <div v-if="isLoading" class="mt-3 p-5">
                <center>
                    <span style="font-size: 48px;">
                        <i class="fas fa-spinner fa-spin"></i><br />
                    </span>
                    Lade Daten..
                </center>
            </div>
            <template v-else>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="account_id">Depot</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm" v-model="form.account_id" id="account_id">
                            <option :value="null">Bitte wählen</option>
                            <option v-for="account in accounts" :value="account.id">{{ account.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="account_id">Investment</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm" v-model="form.investment_id">
                            <option :value="null">Bitte wählen</option>
                            <option v-for="investment in investments" :value="investment.id">{{ investment.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="date_formated">Datum</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="date_formated" v-model="form.date_formated">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="number_of_lots_formated">Stückzahl</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="number_of_lots_formated" v-model="form.number_of_lots_formated">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="security_price_formated">Stückpreis</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="security_price_formated" v-model="form.security_price_formated">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="commission_formated">Kosten</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="commission_formated" v-model="form.commission_formated">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="total_amount">Gesamt</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="total_amount" :value="total_amount_formated""" disabled>
                    </div>
                </div>

            </template>

        </div>
        <div class="card-footer text-right" v-if="! isLoading">
            <button type="submit" class="btn btn-primary btn-sm" @click.prevent="create">Nachkaufen</button>
        </div>
    </div>
</template>

<script>
    export default {

        components: {

        },

        data() {
            var date = new Date();

            if (date.getDate() > 15 && date.getDate() < 4) {
                var day = 25,
                    month = date.getMonth(),
                    commission_formated = '0,99';
            }
            else {
                var day = 4,
                    month = (date.getMonth() + 1);
                    commission_formated = '0,00';
            }

            if((String(day)).length==1) {
                day = '0'+day;
            }
            if((String(month)).length==1) {
                month='0'+month;
            }
            return {
                isCreating: false,
                isLoading: true,
                form: {
                    account_id: 700469,
                    investment_id: 2574357,
                    date_formated: day + '.' + month + '.' + date.getFullYear(),
                    security_price_formated: '0,00',
                    number_of_lots_formated: '0,00',
                    commission_formated: commission_formated,
                },
                accounts: [],
            };
        },

        mounted () {
            this.setAccounts();
        },

        computed: {
            security_price() {
                return Number(this.form.security_price_formated.replace(',', '.'));
            },
            commission() {
                return Number(this.form.commission_formated.replace(',', '.'));
            },
            number_of_lots() {
                return Number(this.form.number_of_lots_formated.replace(',', '.'));
            },
            total_amount() {
                return ((this.security_price * this.number_of_lots) + this.commission);
            },
            total_amount_formated() {
                return this.total_amount.format(2, ',', '.');
            },
            investments() {
                if (this.form.account_id && this.form.account_id in this.accounts) {
                    return this.accounts[this.form.account_id].investments;
                }

                return [];
            }
        },

        methods: {
            setAccounts() {
                var component = this;
                axios.get('/finance/dividends')
                    .then( function (response) {
                        component.accounts = response.data.accounts;
                        component.isLoading = false;
                    });
            },
            create() {
                var component = this;
                component.isCreating = true;
                axios.post('/finance/investments', component.form)
                    .then( function (response) {
                        Vue.success('Anteile erfolgreich gekauft');
                    })
                    .catch( function (error) {
                        console.log(error);
                        Vue.error('Fehler beim Kauf der Anteile');
                    });
            },
        },

    };
</script>