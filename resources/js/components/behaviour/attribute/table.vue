<template>

    <table-base :is-loading="isLoading" :items-length="items.length" :has-filter="hasFilter()" :is-searchable="false" @creating="create">

        <template v-slot:form>
            <div class="form-group mb-0 mr-1">
                <select class="form-control form-control-sm" v-model="form.attribute_id" placeholder="Attribute">
                    <option :value="null">Attribute wählen</option>
                    <optgroup :key="'group_' + group.id" v-for="(group, group_index) in availableAttributeGroups" :label="group.name">
                        <option v-for="attribute in group.attributes" :key="attribute.id" :value="attribute.id">{{ attribute.name }}</option>
                    </optgroup>
                </select>
            </div>
        </template>

        <template v-slot:thead>
            <tr>
                <th>Attribute</th>
                <th>Service</th>
                <th class="text-right">Standard</th>
                <th class="text-right">Ziel</th>
                <th></th>
            </tr>
        </template>

        <template v-slot:tbody>
            <row :item="item" :key="item.id" v-for="(item, index) in items" @deleted="deleted(index)" @updated="updated(index, $event)"></row>
        </template>

    </table-base>

</template>

<script>
    import row from './row.vue';
    import tableBase from '../../tables/base.vue';

    import { baseMixin } from '../../../mixins/tables/base.js';

    export default {

        components: {
            row,
            tableBase,
        },

        mixins: [
            baseMixin,
        ],

        props: {
            attributeGroups: {
                required: true,
                type: Array,
            },
            model: {
                required: true,
                type: Object,
            },
            indexPath: {
                required: false,
                type: String,
                default: null,
            },
        },

        data () {
            return {
                errors: {},
                filter: {
                    //
                },
                form: {
                    attribute_id: null,
                },
                isLoading: false,
                items: this.model.data_attributes,
            };
        },

        computed: {
            availableAttributeGroups() {
                let usesAttributIds = _.map(this.items, 'attribute_id');
                return _.filter(_.cloneDeep(this.attributeGroups), function(group) {
                    group.attributes = _.filter(group.attributes, function(attribute) {
                        return !_.includes(usesAttributIds, attribute.id);
                    });
                    return group.attributes.length > 0;
                });
            },
        },

        methods: {
            create() {
                var component = this;
                axios.post(this.model.attributes_path, component.form)
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
                this.items.unshift(item);
            },
            resetForm() {
                this.form.attribute_id = null;
            },
            fetch() {
                this.items = this.model.data_attributes;
            }
        },
    };
</script>
