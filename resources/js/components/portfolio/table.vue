<template>

    <table-base :is-loading="isLoading" :has-create-button="false" :items-length="items.length" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

        <template v-slot:thead>
            <tr>
                <th class="" width="50">Jahr</th>
                <th class="text-right">Start</th>
                <th class="text-right">Ende</th>
                <th class="text-right">Investiert</th>
                <th class="text-right">Dividenden</th>
                <th class="text-right">Dividendenrendite</th>
                <th class="text-right">1€ / Monat</th>
                <th class="text-right">Investmentrate</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items"></row>
        </template>

    </table-base>

</template>

<script>
    import row from "./row.vue";
    import tableBase from '../tables/base.vue';

    import { baseMixin } from '../../mixins/tables/base.js';

    export default {

        components: {
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        data () {

            return {
                //
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