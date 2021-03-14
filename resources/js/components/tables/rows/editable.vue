<template>
    <tr v-if="isEditing">
        <slot name="edit"></slot>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-primary" title="Speichern" @click="$emit('updating')"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="$emit('editing', false)"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <slot name="show"></slot>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <slot name="preBtnGroup"></slot>
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="$emit('editing', true)"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="$emit('destroying')"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>

    export default {

        components: {

        },

        props: {
            isEditing: {
                type: Boolean,
                required: true,
            },
        },

        data () {
            return {

            };
        },

        methods: {

        },
    };
</script>