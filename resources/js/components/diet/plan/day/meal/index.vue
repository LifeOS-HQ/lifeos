<template>
    <div>
        <div class="row flex-nowrap align-items-stretch overflow-auto">
            <div class="col-12 col-md-6 col-lg-4 col-xl-3" v-for="(item, index) in meals">
                <show :item="item" :foods="foods" :key="index" @deleted="remove(index)" @updated="updated(index, $event)"></show>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 d-flex align-items-center justify-content-center mb-3">
                <button class="btn btn-primary btn-sm" @click="store()"><i class="fas fa-plus-square"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
    import show from './show.vue';

    export default {

        components: {
            show,
        },

        props: {
            foods: {
                required: true,
                type: Array,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                meals: this.model.meals,
            };
        },

        methods: {
            store() {
                var component = this;
                axios.post(component.model.meals_path)
                    .then(function (response) {
                        component.errors = {};
                        component.meals.push(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht erzeugt werden!');
                    });
            },
            remove(index) {
                this.meals.splice(index, 1);
            },
            updated(index, item) {
                Vue.set(this.meals, index, item);
            },
        },
    };
</script>