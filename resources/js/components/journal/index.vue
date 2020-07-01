<template>
    <div class="row">
        <div class="col-2">
            <ul class="list-group mt-0 text-center">
                <li class="list-group-item"><button class="btn btn-primary" @click="store">Anlegen</button></li>
                <li class="list-group-item text-center" v-if="isLoading"><i class="fas fa-spinner fa-spin"></i></li>
                <li class="list-group-item pointer" :item="item" :key="item.id" :uri="uri" v-for="(item, index) in items" @click="show(index, item)" v-else>{{ item.name }}</li>
            </ul>
        </div>
        <div class="col-10">
            <show :item="item" :activities="activities" @deleted="remove(index)" @updated="updated(index, $event)"></show>
        </div>
    </div>
</template>

<script>
    import show from './show.vue';

    export default {

        components: {
            show,
        },

        props: {
            activities: {
                required: true,
                type: Array,
            },
        },

        data() {
            return {
                errors: {},
                uri: '/journal',
                item: null,
                index: null,
                items: [],
                filter: {
                    page: 1,
                },
                form: {

                },
                isLoading: true,
                paginate: {
                    nextPageUrl: null,
                    prevPageUrl: null,
                    lastPage: 0,
                },
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
                        component.items = response.data.data;
                        component.filter.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
                        if (component.items.length > 0 && component.filter.page == 1) {
                            component.item = component.items[0];
                            component.index = 0;
                        }
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        Vue.error('Tagbucheinträge konnten nicht geladen werden!');
                        console.log(error);
                    });
            },
            show(index, item) {
                this.index = index;
                this.item = item;
            },
            store() {
                var component = this;
                axios.post(component.uri, component.form)
                    .then(function (response) {
                        component.items.unshift(response.data);
                        component.item = response.data;
                        Vue.success('Tagebucheintrag erstellt.');
                    })
                    .catch( function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Tagebucheintrag konnte nicht erstellt werden!');
                });
            },
            search() {
                this.filter.page = 1;
                this.fetch();
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                this.item = item;
            },
            remove(index) {
                this.items.splice(index, 1);
                if (this.items.length > 0) {
                    this.item = this.items[0];
                    this.index = 0;
                }
                else
                {
                    this.item = null;
                    this.index = null;
                }
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