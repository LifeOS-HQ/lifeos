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
        fetched(response) {
            this.items = response.data.data;
            this.filter.page = response.data.current_page;
            this.paginate.nextPageUrl = response.data.next_page_url;
            this.paginate.prevPageUrl = response.data.prev_page_url;
            this.paginate.lastPage = response.data.last_page;
            this.paginate.currentPage = response.data.current_page;
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