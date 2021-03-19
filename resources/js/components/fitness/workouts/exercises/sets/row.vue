<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">{{ index + 1 }}</td>
            <td class="align-middle pointer">
                <input-text v-model="form.weight_in_kg_formatted" placeholder="Name" :error="error('weight_in_kg_formatted')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle pointer">
                <input-text v-model="form.reps_count" placeholder="Name" :error="error('reps_count')" @keydown.enter="update"></input-text>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ index + 1 }}</td>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.weight_in_kg_formatted }}</td>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.reps_count }}</td>
        </template>

        <template v-slot:preBtnGroup>

        </template>

    </editable>

</template>

<script>
    import editable from '../../../../tables/rows/editable';
    import inputText from '../../../../forms/inputs/text.vue';

    import { editableMixin } from "../../../../../mixins/tables/rows/editable.js";

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        props: {
            index: {
                required: true,
                type: Number,
            },
        },

        data () {
            return {
                form: {
                    weight_in_kg_formatted: this.item.weight_in_kg_formatted,
                    reps_count: this.item.reps_count,
                },
                isEditing: false,
            };
        },

        methods: {
            show() {
                location.href = this.item.path;
            }
        },

    };
</script>