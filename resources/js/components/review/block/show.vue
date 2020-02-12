<template>
    <div class="card mb-3" v-if="isEditing">
        <div class="card-header d-flex align-items-center">
            <div class="col px-0 mr-3">
                <input class="form-control align-middle" :class="'title' in errors ? 'is-invalid' : ''" type="text" v-model="form.title" @keydown.enter="update">
                <div class="invalid-feedback" v-text="'title' in errors ? errors.text[0] : ''"></div>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
                <button class="btn btn-primary" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
                <textarea class="form-control" rows="3" v-model="form.body"></textarea>
            </div>
        </div>
    </div>
    <div class="card mb-3" v-else>
        <div class="card-header d-flex align-items-center">
            <div class="col px-0">{{ item.title }}</div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </div>
        <div class="card-body pointer white-space-pre" v-html="item.body" @click="isEditing = true"></div>
    </div>
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
                    title: this.item.title,
                    body: this.item.body,
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
        },
    };
</script>