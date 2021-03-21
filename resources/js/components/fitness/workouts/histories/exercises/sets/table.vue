<template>

    <table-base :is-loading="false" :items-length="items.length" :has-filter="false" @creating="create">

        <template v-slot:form>

        </template>

        <template v-slot:filter>

        </template>

        <template v-slot:thead>
            <tr>
                <th class="">#</th>
                <th class="">Gewicht</th>
                <th class="">Wiederholungen</th>
                <th class="text-right d-none d-sm-table-cell w-action">Aktion</th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :index="index" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from "./row.vue";
    import tableBase from '../../../../../tables/base.vue';

    export default {

        components: {
            row,
            tableBase,
        },

        mixins: [
            //
        ],

        props: {
            indexPath: {
                type: String,
                required: true,
            },
            model: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                items: this.model.sets,
            };
        },

        computed: {
            //
        },

        methods: {
            create() {
                var component = this;
                axios.post(this.indexPath, component.form)
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
                this.items.push(item);
            },
            resetErrors() {
                this.errors = {};
            },
            resetForm() {
                this.resetErrors();
                for (var index in this.form) {
                    this.form[index] = '';
                }
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