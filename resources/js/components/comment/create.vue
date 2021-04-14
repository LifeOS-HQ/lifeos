<template>
    <div>
        <div class="form-group">
            <textarea class="form-control form-control-sm" rows="5" v-model="form.body"></textarea>
        </div>

        <button class="btn btn-primary btn-sm" @click="create">Kommentieren</button>
    </div>
</template>

<script>
    export default {

        props: {
            model: {
                required: true,
                type: Object,
            },
        },

        data () {
            return {
                form: {
                    body: '',
                },
            };
        },

        methods: {
            create() {
                var component = this;
                axios.post(component.model.comments_path, component.form)
                    .then(function (response) {
                        component.$emit('created', response.data);
                        component.form.body = '';
                    });
            },
        }

    };
</script>