<template>
    <a href="#" class="list-group-item list-group-item-action">
        <div class="d-flex w-100 justify-content-between">
          <small class="flex-grow-1">{{ item.created_at_formatted }}</small>
          <small><i class="fas fa-fw fa-trash" @click="destroy()"></i></small>
        </div>
        <p class="mb-1" v-html="item.body"></p>
    </a>
</template>

<script>
    export default {

        props: {
            item: {
                required: true,
                type: Object,
            }
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
                        component.$emit("deleted", component.item);
                })
                    .catch(function (error) {
                        component.errors = error.response.data.errors;
                        Vue.errorDelete(component.item);
                });
            },
        },

    };
</script>