<template>

    <div>

        <div class="row mb-3">
            <div class="col d-flex align-items-start mb-1 mb-sm-0">
                <div class="form-group mb-0 mr-1">
                    <select class="form-control form-control-sm" v-model="form.exercise_id">
                        <option :value="0">Übung wählen</option>
                        <option :value="exercise.id" v-for="(exercise, index) in exercises">{{ exercise.name }}</option>
                    </select>
                </div>
                <button class="btn btn-primary btn-sm" @click="create"><i class="fas fa-plus-square"></i></button>
            </div>
            <div class="col-auto d-flex">

            </div>
        </div>

        <show :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></show>

    </div>

</template>

<script>
    import show from './show.vue';

    export default {

        components: {
            show,
        },

        mixins: [
            //
        ],

        props: {
            exercises: {
                required: true,
                type: Array,
            },
            indexPath: {
                type: String,
                required: true,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {
            availableExercises() {
                return [];
            },
        },

        data () {
            return {
                filter: {
                    show: false,
                    searchtext: '',
                },
                form: {
                    exercise_id: 0,
                },
                items: this.model.exercises,
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.indexPath, component.form)
                    .then(function (response) {
                        component.resetForm();
                        component.created(response.data);
                        Vue.successCreate(response.data);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorCreate();
                });
            },
            created(item) {
                console.log(item);
                this.items.unshift(item);
            },
            resetErrors() {
                this.errors = {};
            },
            resetForm() {
                this.resetErrors();
                this.form.exercise_id = 0;
            },
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            },
            deleted(index) {
                var item = this.items[index];
                this.items.splice(index, 1);
                Vue.successDelete(item);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
                Vue.successUpdate(item);
            },
        },
    };
</script>