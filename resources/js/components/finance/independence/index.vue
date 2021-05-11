<template>
    <div>

        <div class="row align-items-stretch">

            <div class="col-12 col-md-6 mb-3">

                <div class="card h-100">
                    <div class="card-header">Ausgaben</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="expenses_month">Ausgaben Monat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="expenses_month" v-model="form.expenses.month" @input="form.expenses.year = $event.target.value * 12; setInvestmentsMonth();">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="expenses_year">Ausgaben Jahr</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="expenses_year" v-model="form.expenses.year" @input="form.expenses.month = $event.target.value / 12; setInvestmentsMonth();">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-md-6 mb-3">

                <div class="card h-100">
                    <div class="card-header">Sparrate</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="income_month">Einnahmen Monat (netto)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="income_month" v-model="form.income.month" @input="form.income.year = $event.target.value * 12; setInvestmentsMonth();">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="income_year">Einnahmen Jahr</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="income_year" v-model="form.income.year" @input="form.income.month = $event.target.value / 12; setInvestmentsMonth();">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="investments_month">Investments Monat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="investments_month" v-model="form.investments.month" @input="form.investments.year = $event.target.value * 12">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="investments_year">Investments Jahr</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="investments_year" v-model="form.investments.year" @input="form.investments.month = $event.target.value / 12">
                            </div>
                        </div>

                    </div>
                </div>

            </div>


            <div class="col-12 col-md-6 mb-3">

                <div class="card h-100">
                    <div class="card-header">Depotgröße</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="expenses_month">Entnahmerate (%)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="expenses_month" v-model="form.investments.withdrawrate">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="networth">Depotgröße</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm disabled" id="networth" :value="networth.format(2, ',', '.')" readonly="readonly">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="user_age">Alter</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="user_age" v-model="user.age">
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-md-6 mb-3">

                <div class="card h-100">
                    <div class="card-header">Dauer</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="investments_start_amount">Startwert</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="investments_start_amount" v-model.number="form.investments.start_amount">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="investments_return">Rendite (%)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm" id="investments_return" v-model="form.investments.return">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="savingsrate">Sparrate (%)</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm disabled" id="savingsrate" :value="(savingsrate * 100).format(2, ',', '.')" readonly="readonly">
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="row mt-3">

            <div class="col">


                <div class="card" v-if="years.length">
                    <div class="card-header">Depot</div>
                    <div class="card-body">
                        <highcharts :options="chartOptions"></highcharts>
                        <table class="table table-fixed table-hover table-striped table-sm bg-white mt-1">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th class="text-right" width="50">Jahr</th>
                                    <th class="text-right" width="50">Alter</th>
                                    <th class="text-right">Start</th>
                                    <th class="text-right">Einzahlungen</th>
                                    <th class="text-right">Rendite</th>
                                    <th class="text-right">Ende</th>
                                    <th class="text-right">Auszahlung Jahr</th>
                                    <th class="text-right">Auszahlung Monat</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="year in years" :class="(year.is_networth_goal ? 'table-success' : '')">
                                    <td>{{ year.n }}</td>
                                    <td class="text-right">{{ year.year }}</td>
                                    <td class="text-right">{{ year.age }}</td>
                                    <td class="text-right">{{ year.start.format(2, ',', '.') }}</td>
                                    <td class="text-right">{{ year.investments.format(2, ',', '.') }}</td>
                                    <td class="text-right">{{ year.interest.format(2, ',', '.') }}</td>
                                    <td class="text-right">{{ year.end.format(2, ',', '.') }}</td>
                                    <td class="text-right">{{ year.withdraw_year.format(2, ',', '.') }}</td>
                                    <td class="text-right">{{ (year.withdraw_year / 12).format(2, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"></td>
                                    <td class="text-right font-weight-bold">{{ form.investments.sum.format(2, ',', '.') }}</td>
                                    <td class="text-right font-weight-bold">{{ form.investments.interest_sum.format(2, ',', '.') }}</td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                    <td class="text-right"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

</template>

<script>
    import {Chart} from 'highcharts-vue'

    export default {

        components: {
            highcharts: Chart,
        },

        mixins: [
            //
        ],

        props: {
            //
        },

        data () {

            return {
                form: {
                    expenses: {
                        month: 500,
                        year: 6000,
                    },
                    income: {
                        month: 4500,
                        year: 54000
                    },
                    investments: {
                        month: 4000,
                        year: 48000,
                        withdrawrate: 3,
                        return: 6,
                        start_amount: 200000,
                        interest_sum: 0,
                        sum: 0,
                    },
                },
                user: {
                    age: 34,
                },
                chartOptions: {
                    chart: {
                        type: 'area',
                    },
                    xAxis: {
                        categories: [],
                    },
                    yAxis: [
                        {
                            min: 0,
                            title: {
                                text: 'Euro (€)'
                            },
                        },
                    ],
                    plotOptions: {
                        area: {
                            stacking: 'normal',
                        },
                    },
                    tooltip: {
                        useHTML: true,
                        shared: true,
                        formatter: function() {
                            var points = '<div><b>' + this.x + ': ' + this.points[0].total.format(0, ',', '.') + ' €</b></div>';
                            $.each(this.points, function (i, point){
                                points += '<div style="color: ' + point.series.color + '">' + point.series.name + ': ' + point.y.format(0, ',', '.') + ' €</div>';
                            });
                            return points;
                        }
                    },
                    title: {
                        text: '',
                    },
                    series: [],
                },
            };

        },

        computed: {
            savingsrate() {
                if (this.form.investments.year == 0 || this.form.income.year == 0) {
                    return 0;
                }
                return this.form.investments.year / this.form.income.year;
            },
            networth() {
                if (this.form.investments.withdrawrate == 0 || this.form.expenses.year == 0) {
                    return 0;
                }
                return this.form.expenses.year / (this.form.investments.withdrawrate / 100);
            },
            years() {
                var years = [],
                    start_amount = this.form.investments.start_amount || 0,
                    investments_year = this.form.investments.year,
                    interest = 0,
                    end_amount = 0,
                    is_networth_goal = false,
                    is_networth_goal_reached = false,
                    year_start = (new Date()).getFullYear(),
                    age = this.user.age,
                    series = [
                        {
                            color: '#f7a35c',
                            name: 'Rendite',
                            data: [],
                        }, {
                            color: '#7cb5ec',
                            name: 'Investments',
                            data: [],
                        },
                    ];

                this.form.investments.sum = start_amount;
                this.form.investments.interest_sum = 0;

                if (this.networth == 0) {
                    return years;
                }

                for (var i = 0; i < 20; i++) {
                    interest = (start_amount + investments_year) * (this.form.investments.return / 100);
                    end_amount = start_amount + investments_year + interest;
                    is_networth_goal = false;
                    if  (is_networth_goal_reached == false && end_amount >= this.networth) {
                        is_networth_goal = true;
                        is_networth_goal_reached = true;
                    }

                    var year = {
                        n: i + 1,
                        year: year_start,
                        age: age,
                        start: start_amount,
                        investments: investments_year,
                        interest: interest,
                        end: end_amount,
                        is_networth_goal: is_networth_goal,
                        withdraw_year: (end_amount * this.form.investments.withdrawrate / 100)
                    };
                    years.push(year);
                    start_amount = end_amount;

                    this.chartOptions.xAxis.categories.push(year_start);

                    this.form.investments.sum += investments_year;
                    this.form.investments.interest_sum += interest;
                    year_start++;
                    age++;

                    series[1].data.push(this.form.investments.sum);
                    series[0].data.push(this.form.investments.interest_sum);
                }

                this.chartOptions.series = series;

                return years;
            },
        },

        methods: {
            setInvestmentsMonth() {
                if (this.form.income.year < this.form.expenses.year) {
                    this.form.investments.month = 0;
                }
                else {
                    this.form.investments.month = (this.form.income.year - this.form.expenses.year) / 12;
                }
                this.form.investments.year = this.form.investments.month * 12;
            },
        },
    };
</script>