<template>
    <div class="card mb-3" v-if="isEditing">
        <div class="card-header d-flex align-items-center">
            <div class="col px-0 mr-3">
                <select class="form-control" v-model="form.activity_id">
                    <option :value="activity.id" v-for="(activity, index) in activities">{{ activity.title }}</option>
                </select>
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
                <textarea class="form-control" rows="3" v-model="form.comment"></textarea>
            </div>
        </div>
        <div class="card-footer">
            <select class="form-control" :class="'rating' in errors ? 'is-invalid' : ''" v-model="form.rating">
                <option :value="null">Keine Bewertung</option>
                <option :value="n" v-for="n in 10">{{ n }}</option>
            </select>
        </div>
    </div>
    <div class="card mb-3" v-else>
        <div class="card-header d-flex align-items-center">
            <div class="col px-0 pointer" @click="isEditing = true">{{ item.activity.title }}</div>
            <div class="btn-group btn-group-sm" role="group">
                <button class="btn btn-primary" @click="isEditing = true"><i class="fas fa-fw fa-edit"></i></button>
                <button type="button" class="btn btn-secondary" title="Löschen" @click="destroy"><i class="fas fa-fw fa-trash"></i></button>
            </div>
        </div>
        <div class="card-body pointer" v-html="item.comment" @click="isEditing = true"></div>
        <div class="card-footer pointer" @click="isEditing = true">Bewertung: {{ item.rating || 'Keine Bewertung' }}</div>
    </div>
</template>

<script>
    export default {

        props: {
            item: {
                required: true,
                type: Object,
            },
            activities: {
                required: true,
                type: Array,
            },
        },

        data() {
            return {
                form: {
                    activity_id: this.item.activity_id,
                    comment: this.item.comment,
                    rating: this.item.rating || null,
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