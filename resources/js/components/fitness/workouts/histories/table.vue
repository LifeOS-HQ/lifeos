<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>

        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Datum</th>
                <th class="text-right">Distanz</th>
                <th class="text-right">Dauer</th>
                <th class="text-right">Steigung</th>
                <th class="text-right">Kalorien</th>
                <th class="text-right">Puls</th>
                <th class="text-right">Power</th>
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
