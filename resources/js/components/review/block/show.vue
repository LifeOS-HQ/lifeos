<template>
    <div class="card mb-3" v-if="isEditing">
        <div class="card-header d-flex align-items-center">
            <div class="col px-0 mr-3">
                <input class="form-control form-control-sm align-middle" :class="'title' in errors ? 'is-invalid' : ''" type="text" v-model="form.title" @keydown.enter="update">
                <div class="invalid-feedback" v-text="'title' in errors ? errors.text[0] : ''"></div>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="update"><i class="fas fa-fw fa-save"></i></button>
                <button class="btn btn-secondary" @click="isEditing = false"><i class="fas fa-fw fa-times"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group mb-0">
                <textarea class="form-control form-control-sm" rows="10" v-model="form.body"></textarea>
            </div>
            <small><a class="text-body" href="https://guides.github.com/features/mastering-markdown/" target="_blank">Styling with Markdown is supported</a></small>
        </div>
    </div>
    <div class="card mb-3" v-else>
        <div class="card-header d-flex align-items-center">
            <div class="col px-0">{{ item.title }}</div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-secondary" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </div>
        <div class="card-body" v-html="item.body_markdown"></div>
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