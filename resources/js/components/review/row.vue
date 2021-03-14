<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.at_formatted" placeholder="Name" :error="error('at_formatted')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle pointer">
                <input-text v-model="form.title" placeholder="Name" :error="error('title')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle text-right">{{ item.lifearea_ratings_avg_formatted }}</td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="show">{{ item.at_formatted }}</td>
            <td class="align-middle pointer" @click="show">{{ item.title }}</td>
            <td class="align-middle text-right pointer" @click="show">{{ item.lifearea_ratings_avg_formatted }}</td>
        </template>

        <template v-slot:preBtnGroup>
            <button type="button" class="btn btn-secondary" title="Anzeigen" @click="show"><i class="fas fa-fw fa-eye"></i></button>
        </template>

    </editable>

</template>

<script>
    import editable from '../tables/rows/editable';
    import inputText from '../forms/inputs/text.vue';

    import { editableMixin } from "../../mixins/tables/rows/editable.js";

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
                    at_formatted: this.item.at_formatted,
                    title: this.item.title,
                },
            };
        },

        methods: {
            show() {
                location.href = this.item.path;
            }
        },

    };
</script>