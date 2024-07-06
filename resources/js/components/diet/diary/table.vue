<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.at_formatted" placeholder="Datum" :error="error('at_formatted')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th width="100" class="">Datum</th>
                <th class="text-right" width="100">Kalorien</th>
                <th class="text-right" width="100">Kohlenhydrate</th>
                <th class="text-right" width="100">Fett</th>
                <th class="text-right" width="100">Protein</th>
                <th class="text-left" width="100%">Notiz</th>
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
            //
        },

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    at_formatted: this.getAtFormatted(),
                },
            };
        },

        computed: {

        },

        methods: {
            created(item) {
                location.href = item.path;
            },
            getAtFormatted() {
                const today = new Date();
                const yyyy = today.getFullYear();
                let mm = today.getMonth() + 1;
                let dd = today.getDate();

                if (dd < 10) {
                    dd = '0' + dd;
                }
                if (mm < 10) {
                    mm = '0' + mm;
                }

                return dd + '.' + mm + '.' + yyyy;
            },
        },
    };
</script>
