<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>

        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Datum</th>
                <th class="">Titel</th>
                <th class="text-right">Ø Bewertung</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from "./row.vue";
    import tableBase from '../tables/base.vue';

    import { baseMixin } from "../../mixins/tables/base.js";
    import { paginatedMixin } from "../../mixins/tables/paginated.js";

    export default {

        components: {
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        props: {

        },

        data () {
            return {

            };
        },

        computed: {
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
            created(item) {
                location.href = item.path;
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