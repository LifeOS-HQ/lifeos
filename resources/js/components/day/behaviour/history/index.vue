<template>
    <div class="row">

        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>Verhalten</div>
                        <div>
                            <span class="badge badge-pill pointer" :class="filter.status == 'all' ? 'badge-primary' : 'badge-light'" @click="setStatus('all')">Alle</span>
                            <span class="badge badge-pill pointer" :class="filter.status == 'incompleted' ? 'badge-primary' : 'badge-light'" @click="setStatus('incompleted')">Fällig</span>
                            <span class="badge badge-pill pointer" :class="filter.status == 'completed' ? 'badge-primary' : 'badge-light'" @click="setStatus('completed')">Erledigt</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <list :items="filteredItems" :item-to-show="item_to_show" @show="show($event)" @complete="complete($event)" @incomplete="incomplete($event)"></list>
                </div>
            </div>
        </div>

        <div class="col">

            <div class="card mb-3">
                <div class="card-header">
                    Attribute
                </div>
                <div class="card-body">
                    <table class="table table-fixed table-hover table-striped table-sm bg-white">
                        <thead>
                            <tr>
                                <th>Attribut</th>
                                <th>Wert</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr :key="value.id" v-for="(value, index) in model.values">
                                <td>{{ value.attribute.name }}</td>
                                <td>{{ value.formatted_value }} {{ value.attribute.unit }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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
    import list from './list.vue';

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
            filteredItems() {
                if (this.filter.status === 'all') {
                    return this.items;
                }

                return _.pickBy(this.items, (item, index) => {
                    return item.is_completed == (this.filter.status === 'completed');
                });
            },
        },

        mounted() {
            if (this.items.filter(item => item.is_completed == 0).length === 0) {
                this.filter.status = 'all';
            }
            this.setFirstFilteredItemtoShow();
        },

        data() {
            return {
                items: this.model.behaviour_histories,
                item_to_show: null,
                filter: {
                    status: 'incompleted',
                }
            };
        },

        methods: {
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
                let next_key;
                const filtered_item_keys = Object.keys(this.filteredItems);

                if (filtered_item_keys.length === 0) {
                    this.item_to_show = null;
                    return;
                }

                if (!this.item_to_show) {
                    next_key = filtered_item_keys[0];
                }
                else {
                    const current_key_of_keys = filtered_item_keys.indexOf(this.item_to_show.index);

                    if (current_key_of_keys === -1) {
                        next_key = filtered_item_keys.find(key => {
                            return key > this.item_to_show.index;
                        });
                    }

                    if (! next_key) {
                        const next_key_of_keys = (current_key_of_keys + 1) % filtered_item_keys.length;
                        next_key = filtered_item_keys[next_key_of_keys];
                    }
                }

                this.show({
                    index: next_key,
                    item: this.filteredItems[next_key],
                });
            },
            previous() {
                let previous_index;
                const filtered_item_keys = Object.keys(this.filteredItems);
                const last_index = filtered_item_keys[filtered_item_keys.length - 1];

                if (filtered_item_keys.length === 0) {
                    this.item_to_show = null;
                    return;
                }

                if (!this.item_to_show) {
                    previous_index = filtered_item_keys[last_index];
                }
                else {
                    const current_key_of_keys = filtered_item_keys.indexOf(this.item_to_show.index);
                    const previous_key_of_keys = (current_key_of_keys - 1 + filtered_item_keys.length) % filtered_item_keys.length;
                    previous_index = filtered_item_keys[previous_key_of_keys];
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
                        component.next();
                        Vue.success('Du hast <b>' + response.data.behaviour.name + '</b> erledigt.<br >That\'s like you!');
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
            setStatus(status) {
                this.filter.status = status;
                this.setFirstFilteredItemtoShow();
            },
            setFirstFilteredItemtoShow() {
                const filtered_item_keys = Object.keys(this.filteredItems);
                if (filtered_item_keys.length > 0) {
                    const first_index = filtered_item_keys[0];
                    this.item_to_show = {
                        index: first_index,
                        item: this.filteredItems[first_index],
                    };
                }
                else {
                    this.item_to_show = null;
                }
            },
        },
    };
</script>
