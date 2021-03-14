export const paginatedMixin = {

    data () {
        return {
            filter: {
                page: 1,
            },
            paginate: {
                nextPageUrl: null,
                prevPageUrl: null,
                lastPage: 0,
                currentPage: 0,
            },
        };
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
    },

    methods: {
        fetched(response) {
            this.items = response.data.data;
            this.filter.page = response.data.current_page;
            this.paginate.nextPageUrl = response.data.next_page_url;
            this.paginate.prevPageUrl = response.data.prev_page_url;
            this.paginate.lastPage = response.data.last_page;
            this.paginate.currentPage = response.data.current_page;
        }
    },
};