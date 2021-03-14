<template>
    <div>
        <show :item="item" :key="index" v-for="(item, index) in blocks" @deleted="remove(index)" @updated="updated(index, $event)"></show>
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
            model: {
                required: true,
                type: Object,
            },
        },

        data() {
            return {
                blocks: this.model.blocks,
            };
        },

        methods: {
            store() {
                var component = this;
                axios.post(component.model.path + '/block')
                    .then(function (response) {
                        component.errors = {};
                        component.blocks.push(response.data);
                    })
                    .catch(function (error) {
                        console.log(error);
                        component.errors = error.response.data.errors;
                        Vue.error('Datensatz konnte nicht erzeugt werden!');
                    });
            },
            remove(index) {
                this.blocks.splice(index, 1);
            },
            updated(index, item) {
                Vue.set(this.blocks, index, item);
            },
        },
    };
</script>