<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>

        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="" width="100%">Datum</th>
                <th class="text-right d-none d-sm-table-cell w-action" width="125">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../../tables/base.vue';
    import inputText from '../../../forms/inputs/text.vue';

    import { baseMixin } from '../../../../mixins/tables/base.js';
    import { paginatedMixin } from "../../../../mixins/tables/paginated.js";

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
            paginatedMixin,
        ],

        data () {
            return {

            };
        },

        methods: {
            created(item) {
                location.href = item.path;
            },
        },
    };
</script>