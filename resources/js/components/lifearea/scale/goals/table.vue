<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" @searching="searching($event)" @creating="create">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <select class="form-control form-control-sm" v-model="form.data_attribute_id">
                    <option :value="0">Attribut wählen</option>
                    <optgroup :label="group.name" v-for="(group, group_index) in attribute_groups">
                        <option :value="attribute.id" v-for="(attribute, attribute_index) in group.attributes">{{ attribute.id }} {{ attribute.name }}</option>
                    </optgroup>
                </select>
            </div>
        </template>

        <template v-slot:thead>
            <tr>
                <th>Attribut</th>
                <th width="">Start</th>
                <th class="d-none d-sm-table-cell" width="">Ende</th>
                <th class="text-right" width="100">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" :attribute_groups="attribute_groups" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import inputText from '../../../forms/inputs/text.vue';
    import tableBase from '../../../tables/base.vue';

    import { baseMixin } from "../../../../mixins/tables/base.js";

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
            attribute_groups: {
                required: true,
                type: Array,
            },
        },

        computed: {
            selected_attribute_ids() {
                return this.items.reduce( function (total, goal) {
                    total.push(goal.data_attribute_id);
                    return total;
                }, []);
            },
            // available_contacts() {
            //     var component = this;
            //     return this.contacts.filter(function (contact) {
            //         return (component.selected_ids.indexOf(contact.id) == -1);
            //     })
            // },
        },

        data () {
            return {
                filter: {
                    //
                },
                form: {
                    data_attribute_id: 0,
                },
            };
        },

        methods: {
            resetForm() {
                this.form.data_attribute_id = 0;
            },
        }

    };
</script>