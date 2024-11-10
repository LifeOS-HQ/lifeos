<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">{{ item.attribute.name }}</td>
            <td class="align-middle pointer">{{ item.service_slug }}</td>
            <td class="align-middle pointer">
                <input-text v-model="form.default_number_formatted" placeholder="Wert" :error="error('default_number_formatted')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle pointer">
                <input-text v-model="form.goal_number_formatted" placeholder="Wert" :error="error('goal_number_formatted')" @keydown.enter="update"></input-text>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.attribute.name }}</td>
            <td class="align-middle pointer">{{ item.service_slug }}</td>
            <td class="align-middle text-right pointer" @click="isEditing = true">{{ item.default_number_formatted }}</td>
            <td class="align-middle text-right pointer" @click="isEditing = true">{{ item.goal_number_formatted }}</td>
        </template>

    </editable>

</template>

<script>
    import editable from '../../tables/rows/editable';
    import inputText from '../../forms/inputs/text.vue';

    import { editableMixin } from '../../../mixins/tables/rows/editable.js';

    export default {

        components: {
            editable,
            inputText,
        },

        mixins: [
            editableMixin,
        ],

        props: {
            item: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                errors: {},
                form: {
                    service_slug: this.item.service_slug,
                    default_number_formatted: this.item.default_number_formatted,
                    goal_number_formatted: this.item.goal_number_formatted,
                },
            };
        },

        computed: {
            //
        },

        methods: {
            //
        },
    };
</script>
