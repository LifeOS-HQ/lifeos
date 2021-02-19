<template>
    <div class="card">
        <div class="card-header">Level</div>
        <div class="card-body">
            <table class="table table-hover table-striped table-fixed">
                <thead>
                    <tr>
                        <td width="50" class="">#</td>
                        <td width="70%">Beschreibung</td>
                        <td width="20%"></td>
                    </tr>
                </thead>
                <tbody>
                    <show :item="item" :key="index" v-for="(item, index) in items" @deleted="remove(index)" @updated="updated(index, $event)"></show>
                </tbody>
            </table>
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
            model: {
                required: true,
                type: Object,
            },
        },

        computed: {

        },

        data() {
            return {
                items: this.model.scales,
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
        },
    };
</script>