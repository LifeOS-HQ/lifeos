<template>
    <div>
        <div class="row">
            <div class="col mb-1 mb-sm-0">

            </div>
            <div class="col-auto d-flex" v-if="false">
                <div class="form-group" style="margin-bottom: 0;">

                </div>
                <button class="btn btn-secondary ml-1" @click="filter.show = !filter.show"><i class="fas fa-filter"></i></button>
            </div>
        </div>

        <form v-if="filter.show" id="filter" class="mt-1">
            <div  class="form-row">



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
                        <th class="" width="50">Jahr</th>
                        <th class="text-right" width="90">Arbeitstage</th>
                        <th class="text-right" width="90">Arbeitszeit</th>
                        <th class="text-right" width="90">Sollstunden</th>
                        <th class="text-right" width="125">Arbeitszeit / Tag</th>
                        <th class="text-right" width="90">Stundenlohn</th>
                        <th class="text-right" width="90">Aufschlag</th>
                        <th class="text-right" width="80">Lohn</th>
                        <th class="text-right" width="80">Bonus</th>
                        <th class="text-right" width="90">Brutto</th>
                        <th class="text-right" width="125">Steuerrückzahlung</th>
                        <th class="text-right" width="90">Netto</th>
                        <th class="text-right d-none d-sm-table-cell w-action" width="50">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    <row :item="item" :key="item.id" :uri="uri" v-for="(item, index) in items" @updated="updated(index, $event)"></row>
                </tbody>
            </table>
        </div>
        <div class="alert alert-dark mt-3" v-else><center>Keine Jahre vorhanden</center></div>
    </div>
</template>

<script>
    import row from "./row.vue";

    export default {

        components: {
            row,
        },

        props: {

        },

        data () {

            var d = new Date();

            return {
                uri: '/work/year',
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
