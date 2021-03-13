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

                <div class="col-auto">
                    <div class="form-group">
                        <label class="col-form-label col-form-label-sm" for="filter-month">Monat</label>
                        <select class="form-control form-control-sm" id="filter-month" v-model="filter.month" @change="search">
                            <option :value="index" v-for="(month, index) in months">{{ month }}</option>
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
                        <th class="">Datum</th>
                        <th class="">Start</th>
                        <th class="">Ende</th>
                        <th class="text-right">Pause</th>
                        <th class="text-right">Dauer</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in items">
                        <row :item="item" :key="item.id" :uri="uri"></row>
                    </template>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="font-weight-bold">{{ days_count }} Tage</td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class="text-right font-weight-bold">{{ (seconds_break_sum / 60 / 60).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">{{ (seconds_sum / 60 / 60).format(2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold"></td>
                        <td class=""></td>
                        <td class=""></td>
                        <td class="text-right font-weight-bold">Ø {{ (seconds_break_sum / 60 / 60 / days_count).format(2, ',', '.') }}</td>
                        <td class="text-right font-weight-bold">Ø {{ (seconds_sum / 60 / 60 / days_count).format(2, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Artikel vorhanden</center></div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="filter.page--">Previous</a>
                </li>

                <li class="page-item" v-for="(n, i) in pages" v-bind:class="{ active: (n == filter.page) }"><a class="page-link" href="#" @click.prevent="filter.page = n">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="filter.page++">Next</a>
                </li>
            </ul>
        </nav>
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
                uri: '/work/time',
                items: [],
                isLoading: true,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
                seconds_break_sum: 0,
                seconds_sum: 0,
                days_count: 0,
                filter: {
                    show: true,
                    page: 1,
                    year: (this.years.length ? this.years[this.years.length - 1].year : d.getFullYear()),
                    month: d.getMonth() + 1,
                },
                errors: {},
                months: [
                    'Alle',
                    'Januar',
                    'Februar',
                    'März',
                    'April',
                    'Mai',
                    'Juni',
                    'Juli',
                    'August',
                    'September',
                    'Oktober',
                    'November',
                    'Dezember',
                ],
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
                        component.seconds_sum = 0;
                        component.seconds_break_sum = 0;
                        component.days_count = 0;
                        var last_date = '';
                        for (var index in component.items) {
                            component.seconds_break_sum += component.items[index].seconds_break;
                            component.seconds_sum += component.items[index].seconds;
                            if (last_date == component.items[index].date_formatted) {
                                continue;
                            }
                            component.days_count++;
                            last_date = component.items[index].date_formatted;
                        }
                    })
                    .catch(function (error) {
                        Vue.error('Arbeitszeiten konnten nicht geladen werden!');
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