<template>
    <tr v-if="isEditing">
        <td class="align-middle">{{ item.lifearea.title }}</td>
        <td class="align-middle">
            <select class="form-control" v-model="form.rating">
                <option :value="null">Keine Bewertung abgegeben</option>
                <option :value="rating" v-for="rating in 10">{{ rating }}</option>
            </select>
        </td>
        <td class="align-middle">
            <input class="form-control align-middle" :class="'comment' in errors ? 'is-invalid' : ''" type="text" v-model="form.comment" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'comment' in errors ? errors.text[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
                <button class="btn btn-primary" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle">{{ item.lifearea.title }}</td>
        <td class="align-middle text-right">{{ item.rating || 'Keine Bewertung abgegeben' }}</td>
        <td class="align-middle">{{ item.comment }}</td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {

        props: {
            item: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                form: {
                    rating: this.item.rating,
                    comment: this.item.comment,
                },
                errors: {},
                isEditing: this.item.rating == null,
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
    };
</script>