<template>

    <editable :is-editing="isEditing" @editing="isEditing = $event" @updating="update()" @destroying="destroy()">

        <template v-slot:edit>
            <td class="align-middle pointer">
                <input-text v-model="form.name" placeholder="Bezeichnung" :error="error('name')" @keydown.enter="update"></input-text>
            </td>
            <td class="align-middle pointer">
                <input-text v-model="form.valid_from_formatted" placeholder="Gülltig ab" :error="error('valid_from_formatted')" @keydown.enter="update"></input-text>
            </td>
            <td></td>
        </template>

        <template v-slot:show>
            <td class="align-middle pointer" @click="show">{{ item.name }}</td>
            <td class="align-middle pointer" @click="show">{{ item.valid_from_formatted }}</td>
            <td class="align-middle pointer" @click="show">{{ item.is_active ? 'aktiv' : 'inaktiv' }}</td>
        </template>

        <template v-slot:preBtnGroup>
            <button type="button" class="btn btn-secondary" title="Anzeigen" @click="show"><i class="fas fa-fw fa-eye"></i></button>
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
                    valid_from_formatted: this.item.valid_from_formatted
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