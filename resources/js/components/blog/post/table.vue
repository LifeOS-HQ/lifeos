<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.title" placeholder="Name" :error="error('title')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Titel</th>
                <th class="">Veröffentlicht</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import inputText from '../../forms/inputs/text.vue';
    import row from "./row.vue";
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from "../../../mixins/tables/base.js";
    import { paginatedMixin } from "../../../mixins/tables/paginated.js";

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
            //
        },

        data () {

            return {
                form: {
                    title: '',
                },
            };
        },

        computed: {

        },

        methods: {
            created(item) {
                location.href = item.path;
            },
        },
    };
</script>