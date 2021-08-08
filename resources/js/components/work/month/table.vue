<template>
    <div>
        <div class="row" v-if="false">
            <div class="col mb-1 mb-sm-0">

            </div>
            <div class="col-auto d-flex">
                <div class="form-group" style="margin-bottom: 0;">

                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="mt-1">
            <div  class="form-row">

                <div class="col-auto">
                    <div class="form-group">
                        <label class="col-form-label col-form-label-sm" for="filter-year">Jahr</label>
                        <select class="form-control form-control-sm" id="filter-year" v-model="filter.year" @change="search">
                            <option :value="year.year" v-for="(year, index) in years">{{ year.year }}</option>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div v-if="isLoading" class="mt-3 p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="table-responsive mt-3" v-else-if="items.length">
            <table class="table table-fixed table-hover table-striped table-sm bg-white">
                <thead>
                    <tr>
                        <th class="" width="125">Datum</th>
                        <th class="text-right">Arbeitstage</th>
                        <th class="text-right">Arbeitszeit</th>
                        <th class="text-right">Arbeitszeit / Tag</th>
                        <th class="text-right">Lohn</th>
                        <th class="text-right">Aufschlag</th>
                        <th class="text-right">Bonus</th>
                        <th class="text-right">Brutto</th>
                        <th class="text-right">Netto</th>
                        <th class="text-right d-none d-sm-table-cell w-action" width="50">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row :item="item" :key="item.id" :uri="uri" v-for="(item, index) in items" @updated="updated(index, $event)"></row>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="" width="125">{{ items.length }} Monate</td>
                        <td class="text-right font-weight-bold">{{ sum_working_days }}</td>
                        <td class="text-right font-weight-bold">{{ Number(sum_hours_worked).format(2, ',', '.') }}</td>
                        <td class="text-right"></td>
                        <td class="text-right font-weight-bold">{{ Number(sum_wage).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">{{ Number(sum_wage_bonus).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">{{ Number(sum_bonus).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">{{ Number(sum_gross).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">{{ Number(sum_net).format(2, ',', '.') }}</td>
                        <td class="text-right d-none d-sm-table-cell w-action"></td>
                    </tr>
                    <tr>
                        <td class="" width="125"></td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_working_days / completed_months.length).format(0, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_hours_worked / completed_months.length).format(2, ',', '.') }}</td>
                        <td class="text-right"></td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_wage / completed_months.length).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_wage_bonus / completed_months.length).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_bonus / completed_months.length).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_gross / completed_months.length).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Ø {{ Number(sum_net / completed_months.length).format(2, ',', '.') }}</td>
                        <td class="text-right d-none d-sm-table-cell w-action"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Monate vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
        },

        props: {
            years: {
                required: true,
                type: Array,
            },
        },

        data () {

            var d = new Date();

            return {
                uri: '/work/month',
                items: [],
                isLoading: true,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                filter: {
                    show: true,
                    page: 1,
                    year: (this.years.length ? this.years[this.years.length - 1].year : d.getFullYear()),
                },
                errors: {},
            };
        },

        mounted() {

            this.fetch();

        },

        watch: {
            page () {
                this.fetch();
            },
        },

        computed: {
            completed_months() {
                return this.items.filter( function (month) {
                    return (month.net_in_cents > 0);
                })
            },
            page() {
                return this.filter.page;
            },
            pages() {
                var pages = [];
                for (var i = 1; i <= this.paginate.lastPage; i++) {
                    if (this.showPageButton(i)) {
                        const lastItem = pages[pages.length - 1];
                        if (lastItem < (i - 1) && lastItem != '...') {
                            pages.push('...');
                        }
                        pages.push(i);
                    }
                }

                return pages;
            },
            sum_working_days() {
                return this.items.reduce( function(sum, item) {
                    return sum + item.days_worked;
                }, 0);
            },
            sum_hours_worked() {
                return this.items.reduce( function(sum, item) {
                    return sum + Number(item.hours_worked);
                }, 0);
            },
            sum_wage() {
                return this.items.reduce( function(sum, item) {
                    return sum + (item.wage_in_cents / 100);
                }, 0);
            },
            sum_wage_bonus() {
                return this.items.reduce( function(sum, item) {
                    return sum + (item.wage_bonus_in_cents / 100);
                }, 0);
            },
            sum_bonus() {
                return this.items.reduce( function(sum, item) {
                    return sum + (item.bonus_in_cents / 100);
                }, 0);
            },
            sum_gross() {
                return this.items.reduce( function(sum, item) {
                    return sum + (item.gross_in_cents / 100);
                }, 0);
            },
            sum_net() {
                return this.items.reduce( function(sum, item) {
                    return sum + (item.net_in_cents / 100);
                }, 0);
            },
        },

        methods: {
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.uri, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Jahre konnten nicht geladen werden!');
                        console.log(error);
                    });
            },
            search() {
                this.filter.page = 1;
                this.fetch();
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
            remove(index) {
                this.items.splice(index, 1);
            },
            showPageButton(page) {
                if (page == 1 || page == this.paginate.lastPage) {
                    return true;
                }

                if (page <= this.filter.page + 2 && page >= this.filter.page - 2) {
                    return true;
                }

                return false;
            },
        },
    };
</script>