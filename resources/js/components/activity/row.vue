<template>
    <tr v-if="isEditing">
        <td class="align-left">
            <input type="text" class="form-control" :class="'title' in errors ? 'is-invalid' : ''" v-model="form.title" placeholder="Name" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'title' in errors ? errors.title[0] : ''"></div></td>
        <td class="align-middle">
            <select class="form-control" v-model="form.lifearea_id">
                <option :value="null">Ohne Lebensbereich</option>
                <option :value="lifearea.id" v-for="(lifearea, index) in lifeareas">{{ lifearea.title }}</option>
            </select>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" title="Abbrechen" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
                <button type="button" class="btn btn-secondary" title="Speichern" @click="update"><i class="fas fa-fw fa-save"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle pointer" @click="isEditing = true">{{ item.title }}</td>
        <td class="align-middle pointer" @click="isEditing = true">{{ item.lifearea ? item.lifearea.title : '-' }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
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

        props: ['item', 'uri', 'lifeareas'],

        computed: {

        },

        data () {
            return {
                isEditing: false,
                id: this.item.id,
                form: {
                    lifearea_id: this.item.lifearea_id,
                    title: this.item.title,
                },
                errors: {},
            };
        },

        methods: {
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
            link() {
                location.href = this.item.path;
            },
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
        },

        watch: {
            item(value) {
                this.form.title = value.title;
            },
        },
    };
</script>