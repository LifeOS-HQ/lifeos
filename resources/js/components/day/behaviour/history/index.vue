<template>
    <div class="row">

        <div class="col">
            <list :items="items" :item-to-show="item_to_show" @show="show($event)" @complete="complete($event)" @incomplete="incomplete($event)"></list>
        </div>

        <div class="col">
            <show
                :item="item_to_show.item"
                @next="next"
                @previous="previous"
                @complete="complete(item_to_show.index)"
                @incomplete="incomplete(item_to_show.index)"
                v-if="item_to_show"
            ></show>
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
            next() {
                let next_index;
                if (!this.item_to_show) {
                    next_index = 0;
                }
                else {
                    next_index = (this.item_to_show.index + 1) % this.items.length;
                }

                this.show({
                    index: next_index,
                    item: this.items[next_index],
                });
            },
            previous() {
                let previous_index;
                if (!this.item_to_show) {
                    previous_index = this.items.length - 1;
                }
                else {
                    previous_index = (this.item_to_show.index - 1);
                    if (previous_index < 0) {
                        previous_index = this.items.length - 1;
                    }
                }

                this.show({
                    index: previous_index,
                    item: this.items[previous_index],
                });
            },
            complete(index) {
                const component = this;
                const item = component.items[index];
                axios.post(item.complete_path)
                    .then(response => {
                        component.sound();
                        Vue.set(component.items, index, response.data);
                        if (component.item_to_show && component.item_to_show.item.id === item.id) {
                            component.item_to_show.item = response.data;
                        }
                    });
            },
            incomplete(index) {
                const component = this;
                const item = component.items[index];
                axios.delete(item.complete_path)
                    .then(response => {
                        Vue.set(component.items, index, response.data);
                        if (component.item_to_show && component.item_to_show.item.id === item.id) {
                            component.item_to_show.item = response.data;
                        }
                    });
            },
            sound() {
                let audio = new Audio('/audio/daily.mp3');
                audio.play();
            },
        },
    };
</script>
