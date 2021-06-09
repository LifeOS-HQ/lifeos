<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.name" placeholder="Bezeichnung" :error="error('name')" @keydown.enter="update"></input-text>
            </td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="show">{{ item.name }}</td>
        </template>

        <template v-slot:preBtnGroup>
            <button type="button" class="btn btn-secondary" title="Anzeigen" @click="show"><i class="fas fa-fw fa-eye"></i></button>
        </template>

    </editable>

</template>

<script>
    import editable from '../../../tables/rows/editable';
    import inputText from '../../../forms/inputs/text.vue';

    import { editableMixin } from '../../../../mixins/tables/rows/editable.js';

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
                    name: this.item.name,
                },
            };
        },

        methods: {
            show() {
                location.href = this.item.path;
            },
        },

    };
</script>