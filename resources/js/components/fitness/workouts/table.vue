<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.name" placeholder="Name" :error="error('name')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="" width="100%">Titel</th>
                <th class="text-right d-none d-sm-table-cell w-action" width="125">Aktion</th>
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

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        data () {
            return {
                form: {
                    title: '',
                },
            };
        },

        methods: {
            created(item) {
                location.href = item.path;
            },
        },
    };
</script>