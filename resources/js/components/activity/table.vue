<template>

    <table-base :is-loading="isLoading" :paginate="paginate" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <input-text v-model="form.title" placeholder="Name" :error="error('title')" @keydown.enter="create"></input-text>
            </div>
        </template>

        <template v-slot:filter>

            <div class="form-group">
                <label class="col-form-label col-form-label-sm" for="filter-lifearea">Lebensbereich</label>
                <select class="form-control form-control-sm" id="filter-lifearea" v-model="filter.lifearea_id" @change="search">
                    <option :value="null">Alle Lebensbereiche</option>
                    <option :value="0">Ohne Lebensbereich</option>
                    <option :value="lifearea.id" v-for="(lifearea, index) in lifeareas">{{ lifearea.title }}</option>
                </select>
            </div>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Titel</th>
                <th class="">Lebensbereich</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :lifeareas="lifeareas" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../tables/base.vue';
    import inputText from '../forms/inputs/text.vue';

    import { baseMixin } from '../../mixins/tables/base.js';
    import { paginatedMixin } from '../../mixins/tables/paginated.js';

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
            lifeareas: {
                required: true,
                type: Array,
            },
        },

        data () {

            return {
                filter: {
                    lifearea_id: null,
                },
                form: {
                    title: '',
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