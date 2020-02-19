<template>
    <tr v-if="isEditing">
        <td class="align-middle" colspan="2">
            <input class="form-control align-middle" :class="'title' in errors ? 'is-invalid' : ''" type="title" v-model="form.title" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'title' in errors ? errors.title[0] : ''"></div>
        </td>
        <td class="align-middle">
            <select class="form-control" :class="'rating' in errors ? 'is-invalid' : ''" v-model="form.rating">
                <option :value="null">Keine Bewertung</option>
                <option :value="n" v-for="n in 10">{{ n }}</option>
            </select>
            <div class="invalid-feedback" v-text="'rating' in errors ? errors.rating[0] : ''"></div>
        </td>
        <td class="align-middle">
            <input class="form-control" :class="'comment' in errors ? 'is-invalid' : ''" type="text" v-model="form.comment" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'comment' in errors ? errors.comment[0] : ''"></div>
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
        <td class="align-middle pointer" @click="isEditing = true">{{ item.title }}</td>
        <td class="align-middle text-right pointer" @click="isEditing = true">{{ item.rating }}</td>
        <td class="align-middle pointer" @click="isEditing = true">{{ item.comment }}</td>
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
                isEditing: (this.item.rating == null),
                id: this.item.id,
                form: {
                    comment: this.item.comment,
                    rating: this.item.rating || null,
                    title: this.item.title,
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
        },

        watch: {

        },
    };
</script>