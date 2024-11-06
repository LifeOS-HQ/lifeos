<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>


        </template>

        <template v-slot:filter>



        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Datum</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';
    import { paginatedMixin } from '../../../mixins/tables/paginated.js';

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
            model: {
                required: true,
                type: Object,
            },
        },

        data () {

            return {
                filter: {
                    //
                },
                form: {
                    //
                },
            };
        },

        computed: {

        },

        methods: {
            //
        },
    };
</script>
