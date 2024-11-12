<template>
    <div class="row">

        <div class="col">
            <list :items="items" @show="show($event)"></list>
        </div>

        <div class="col">
            <show :item="item_to_show.item" v-if="item_to_show"></show>
        </div>

    </div>
</template>

<script>
    import show from './show.vue';
    import list from './list.vue'

    export default {

        components: {
            show,
            list,
        },

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {

        },

        mounted() {
            if (this.model.behaviour_histories.length > 0) {
                this.item_to_show = {
                    index: 0,
                    item: this.model.behaviour_histories[0],
                };
            }
        },

        data() {
            return {
                items: this.model.behaviour_histories,
                item_to_show: null,
            };
        },

        methods: {
            store() {
                var component = this;
                axios.post(component.model.path + '/scale')
                    .then(function (response) {
                        component.errors = {};
                        component.items.push(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht erzeugt werden!');
                    });
            },
            remove(index) {
                this.items.splice(index, 1);
            },
            updated(index, item) {
                Vue.set(this.items, index, item);
            },
            show(event) {
                this.item_to_show = event;
            },
        },
    };
</script>
