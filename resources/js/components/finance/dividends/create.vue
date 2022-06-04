<template>
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between">
            <div>Dividenden erfassen</div>
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
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="security_price_formated">Dividende</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="security_price_formated" v-model="form.security_price_formated" @input="setTotalAmountFormated">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="tax_amount_formated">Steuern</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="tax_amount_formated" v-model="form.tax_amount_formated" @input="setTotalAmountFormated">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label col-form-label-sm" for="total_amount_formated">Gesamt</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="total_amount_formated" v-model="form.total_amount_formated" @input="setTaxAmountFormated">
                    </div>
                </div>

            </template>

        </div>
        <div class="card-footer text-right" v-if="! isLoading">
            <button type="submit" class="btn btn-primary btn-sm" @click.prevent="create">Anlegen</button>
        </div>
    </div>
</template>

<script>
    export default {

        components: {

        },

        data() {
            var date = new Date(),
                day = date.getDate(),
                month = date.getMonth();
            month = month + 1;
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
                    account_id: 917,
                    investment_id: 1544,
                    date_formated: day + '.' + month + '.' + date.getFullYear(),
                    security_price_formated: '0,00',
                    tax_amount_formated: '0,00',
                    total_amount_formated: '0',
                },
                accounts: [],
            };
        },

        mounted () {
            this.setAccounts();
            this.setTotalAmountFormated();
        },

        computed: {
            security_price() {
                return Number(this.form.security_price_formated.replace(',', '.'));
            },
            tax_amount() {
                return Number(this.form.tax_amount_formated.replace(',', '.'));
            },
            total_amount() {
                return Number(this.form.total_amount_formated.replace(',', '.'));
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
                axios.post('/finance/dividends', component.form)
                    .then( function (response) {
                        Vue.success('Dividenden erfolgreich angelegt');
                    })
                    .catch( function (error) {
                        console.log(error);
                        Vue.error('Fehler beim Anlegen der Dividenden');
                    });
            },
            setTaxAmountFormated() {
                this.form.tax_amount_formated = (this.security_price - this.total_amount).format(2, ',', '.');
            },
            setTotalAmountFormated() {
                this.form.total_amount_formated = (this.security_price - this.tax_amount).format(2, ',', '.');
            },
        },

    };
</script>