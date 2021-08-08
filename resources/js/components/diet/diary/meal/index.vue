<template>
    <div>
        <show :item="item" :foods="foods" :diet_meals="diet_meals" :key="index" v-for="(item, index) in meals" @deleted="remove(index)" @updated="updated(index, $event)"></show>
        <button type="button" class="btn btn-secondary btn-sm btn-block" title="Anlegen" @click="store"><i class="fas fa-fw fa-plus"></i></button>
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
            diet_meals: {
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