<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                {{ item.food.name }}
            </td>
            <td class="align-middle pointer">
                <input-text v-model="form.amount_formatted" placeholder="Abkürzung" :error="error('amount_formatted')" @keydown.enter="update"></input-text>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.food.name }}</td>
            <td class="align-middle text-right pointer" @click="isEditing = true">{{ item.amount_formatted }}</td>
        </template>

    </editable>

</template>

<script>
    import editable from '../../../../tables/rows/editable';
    import inputText from '../../../../forms/inputs/text.vue';

    import { editableMixin } from '../../../../../mixins/tables/rows/editable.js';

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        props: {
            //
        },

        data () {
            return {
                form: {
                    amount_formatted: this.item.amount_formatted,
                },
            };
        },

        methods: {
            //
        },

    };
</script>