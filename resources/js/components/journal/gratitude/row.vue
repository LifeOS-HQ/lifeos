<template>
    <tr v-if="isEditing">
        <td class="align-middle" colspan="2">
            <input class="form-control align-middle" :class="'text' in errors ? 'is-invalid' : ''" type="text" v-model="form.text" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'text' in errors ? errors.text[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
                <button type="button" class="btn btn-secondary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle text-center" width="50"><i class="fas fa-grip-lines pointer sort"></i></td>
        <td class="align-middle pointer" @click="isEditing = true">{{ item.text }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn" :class="item.is_game_changer ? 'btn-primary' : 'btn-secondary'" title="Game changer" @click="toggleGameChanger"><i class="fas fa-star"></i></button>
                <button type="button" class="btn btn-secondary" title="Bearbeiten" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        components: {

        },

        props: {
            item: {
                required: true,
                type: Object,
            },
            uri: {
                required: true,
                type: String,
            },
        },

        computed: {

        },

        data () {
            return {
                isEditing: false,
                id: this.item.id,
                form: {
                    text: this.item.text,
                },
                errors: {},
            };
        },

        methods: {
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        component.isEditing = false;
                        Vue.success('Datensatz gespeichert.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht gespeichert werden.');
                });
            },
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('deleted');
                        Vue.success('Datensatz gelöscht.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht gelöscht werden.');
                });
            },
            toggleGameChanger() {
                var component = this;
                axios.put(component.item.path + '/gamechanger')
                    .then( function (response) {
                        component.errors = {};
                        component.$emit('updated', response.data);
                        component.isEditing = false;
                        Vue.success('Datensatz gespeichert.');
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht gespeichert werden.');
                });
            },
        },

        watch: {

        },
    };
</script>