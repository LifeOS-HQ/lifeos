<template>
    <tr v-if="isEditing">
        <td class="align-middle">{{ item.value }}</td>
        <td class="align-middle">
            <input class="form-control align-middle" :class="'description' in errors ? 'is-invalid' : ''" type="text" v-model="form.description" @keydown.enter="update">
            <div class="invalid-feedback" v-text="'description' in errors ? errors.text[0] : ''"></div>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button class="btn btn-secondary" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </td>
    </tr>
    <tr v-else>
        <td class="align-middle">{{ item.value }}</td>
        <td class="align-middle pointer" @click="show">
            <span>{{ item.description }}</span>
            <span class="text-muted ml-1">X/Y Ziele Z%</span>
        </td>
        <td class="align-middle text-right">
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" @click="show"><i class="fas fa-fw fa-eye"></i></button>
                <button class="btn btn-secondary" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy" v-if="item.is_deletable"><i class="fas fa-fw fa-trash"></i></button>
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
                    description: this.item.description,
                },
                errors: {},
                isEditing: false,
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
            show() {
                location.href = this.item.path;
            },
        },
    };
</script>