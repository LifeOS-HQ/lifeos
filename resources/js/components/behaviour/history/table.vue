<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.end_at_formatted" placeholder="Datum" :error="error('end_at_formatted')" @keydown.enter="create"></input-text>
            </div>
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
    import inputText from '../../forms/inputs/text.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';
    import { paginatedMixin } from '../../../mixins/tables/paginated.js';

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

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        data () {
            const date = new Date();
            return {
                filter: {
                    //
                },
                form: {
                    end_at_formatted: ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + date.getFullYear() + ' ' + ('0' + date.getHours()).slice(-2) + ':' + ('0' + date.getMinutes()).slice(-2),
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
