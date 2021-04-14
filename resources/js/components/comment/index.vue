<template>
    <div class="mb-5">
        <create :model="model" @created="created($event)"></create>
        <br />
        <div v-if="isLoading" class="p-5">
            <center>
                <span style="font-size: 48px;">
                    <i class="fas fa-spinner fa-spin"></i><br />
                </span>
                Lade Daten..
            </center>
        </div>
        <div class="list-group" v-else-if="items.length">
            <show v-for="(item, index) in items" :key="item.id" :item="item" @deleted="deleted(index)"></show>
        </div>
        <div class="alert alert-dark" v-else>
            <center>
                Keine Kommentare vorhanden
            </center>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination" v-show="paginate.lastPage > 1">
                <li class="page-item" v-show="paginate.prevPageUrl">
                    <a class="page-link" href="#" @click.prevent="filter.page--">Previous</a>
                </li>

                <li class="page-item" v-for="n in paginate.lastPage" v-bind:class="{ active: (n == filter.page) }"><a class="page-link" href="#" @click.prevent="filter.page = n">{{ n }}</a></li>

                <li class="page-item" v-show="paginate.nextPageUrl">
                    <a class="page-link" href="#" @click.prevent="filter.page++">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import Create from "./create.vue";
    import Show from "./show.vue";

    export default {

        components: {
            Create,
            Show,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                isLoading: true,
                items: [],
                filter: {
                    page: 1,
                },
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

        computed: {
            page() {
                return this.filter.page;
            },
        },

        watch: {
            page () {
                this.fetch();
            },
        },

        methods: {
            created(item) {
                this.items.unshift(item);
            },
            deleted(index) {
                var item = this.items[index];
                this.items.splice(index, 1);
                Vue.successDelete(item);
            },
            fetch() {
                var component = this;
                component.isLoading = true;
                axios.get(component.model.comments_path, {
                    params: component.filter
                })
                    .then(function (response) {
                        component.items = response.data.data;
                        component.filter.page = response.data.current_page;
                        component.paginate.nextPageUrl = response.data.next_page_url;
                        component.paginate.prevPageUrl = response.data.prev_page_url;
                        component.paginate.lastPage = response.data.last_page;
                        component.isLoading = false;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        },

    };
</script>