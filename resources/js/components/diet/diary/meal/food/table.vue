<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" @creating="create" @paginating="filter.page = $event" @searching="searching($event)">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <select class="form-control form-control-sm" v-model="form.food_id">
                    <option :value="null">Nahrungsmittel auswählen</option>
                    <option :value="food.id" v-for="(food, index) in foods">{{ food.name }}</option>
                </select>
            </div>
        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">Nahrungsmittel</th>
                <th class="text-right">Menge</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :foods="foods" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../../../tables/base.vue';
    import inputText from '../../../../forms/inputs/text.vue';

    import { baseMixin } from '../../../../../mixins/tables/base.js';

    export default {

        components: {
            inputText,
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            foods: {
                required: true,
                type: Array,
            },
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
                    food_id: null,
                },
            };
        },

        computed: {

        },

        methods: {
            resetForm() {
                this.resetErrors();
                this.form.food_id = null;
            },
        },
    };
</script>