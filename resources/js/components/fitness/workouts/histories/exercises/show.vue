<template>

        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <div class="col pl-0">
                    {{ item.exercise.name }}
                </div>
                <div class="d-flex">
                    <div class="col-auto px-0 ml-1">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-secondary btn-sm" title="Löschen" @click="destroy()"><i class="fas fa-fw fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <workouts-histories-exercises-sets-table :index-path="item.sets_index_path" :model="item"></workouts-histories-exercises-sets-table>

            </div>
        </div>

</template>

<script>
    import workoutsHistoriesExercisesSetsTable from './sets/table.vue';

    export default {

        components: {
            workoutsHistoriesExercisesSetsTable,
        },

        mixins: [
            //
        ],

        props: {
            item: {
                required: true,
                type: Object,
            },
        },

        computed: {

        },

        data () {
            return {

            };
        },

        methods: {
            destroy() {
                var component = this;
                axios.delete(component.item.path)
                    .then(function (response) {
                        component.$emit('deleted', component.item.id);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorDelete(component.item);
                });

            },
            error(name) {
                return (name in this.errors ? this.errors[name][0] : '');
            },
            update() {
                var component = this;
                axios.put(component.item.path, component.form)
                    .then( function (response) {
                        component.errors = {};
                        component.isEditing = false;
                        component.$emit('updated', response.data);
                    })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                });
            },
        },
    };
</script>