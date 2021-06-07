<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.title" placeholder="Abkürzung" :error="error('title')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle pointer">
                <select class="form-control form-control-sm" v-model="form.lifearea_id">
                    <option :value="null">Ohne Lebensbereich</option>
                    <option :value="lifearea.id" v-for="(lifearea, index) in lifeareas">{{ lifearea.title }}</option>
                </select>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.title }}</td>
            <td class="align-middle pointer" @click="isEditing = true">{{ item.lifearea ? item.lifearea.title : '-' }}</td>
        </template>

    </editable>

</template>

<script>
    import editable from '../tables/rows/editable';
    import inputText from '../forms/inputs/text.vue';

    import { editableMixin } from '../../mixins/tables/rows/editable.js';

    export default {

        components: {
            editable,
            inputText
        },

        mixins: [
            editableMixin,
        ],

        props: {
            lifeareas: {
                required: true,
                type: Array,
            },
        },

        data () {
            return {
                form: {
                    lifearea_id: this.item.lifearea_id,
                    title: this.item.title,
                },
            };
        },

        methods: {
            //
        },

    };
</script>