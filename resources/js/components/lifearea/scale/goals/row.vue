<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <select class="form-control form-control-sm" v-model="form.data_attribute_id">
                    <optgroup :label="group.name" v-for="(group, group_index) in attribute_groups">
                        <option :value="attribute.id" v-for="(attribute, attribute_index) in group.attributes">{{ attribute.id }} {{ attribute.name }}</option>
                    </optgroup>
                </select>
            </td>
            <td class="align-middle pointer">
                <input-text v-model="form.start_formatted" placeholder="Start" :error="error('start_formatted')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle d-none d-sm-table-cell pointer">
                <input-text v-model="form.end_formatted" placeholder="Ende" :error="error('end_formatted')" @keydown.enter="update"></input-text>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.data_attribute.name }}</td>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.start_formatted }}</td>
            <td class="align-middle d-none d-sm-table-cell pointer" @click="isEditing = true">{{ item.end_formatted }}</td>
        </template>

    </editable>

</template>

<script>
    import editable from '../../../tables/rows/editable';
    import inputText from '../../../forms/inputs/text.vue';

    import { editableMixin } from "../../../../mixins/tables/rows/editable.js";

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        props: {
            attribute_groups: {
                required: true,
                type: Array,
            },
        },

        data () {
            return {
                form: {
                    data_attribute_id: this.item.data_attribute_id,
                    end_formatted: this.item.end_formatted,
                    start_formatted: this.item.start_formatted,
                },
                isEditing: this.item.should_edit || false,
            };
        },

        methods: {

        },
    };
</script>