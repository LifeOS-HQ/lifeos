<template>
    <tr>
        <slot name="show"></slot>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <slot name="preBtnGroup"></slot>
                <button type="button" class="btn btn-secondary" title="Anzeigen" @click="show" v-if="hasShowButton"><i class="fas fa-fw fa-eye"></i></button>
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="edit" v-if="hasEditButton"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="$emit('destroying')"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>

    export default {

        components: {
            //
        },

        props: {
            item: {
                type: Object,
                required: true,
            },
            isSelected: {
                type: Boolean,
                required: false,
                default: false,
            },
            hasEditButton: {
                type: Boolean,
                required: false,
                default: true,
            },
            hasShowButton: {
                type: Boolean,
                required: false,
                default: true,
            },
        },

        data () {
            return {

            };
        },

        methods: {
            edit() {
                location.href = this.item.edit_path;
            },
            show() {
                location.href = this.item.path;
            }
        },
    };
</script>