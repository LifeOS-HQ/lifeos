<template>
    <div class="form-group mb-0 mr-1">
        <v-select :value="value" :options="diet_meals" label="Mahlzeit" @input="$emit('input', $event)"></v-select>
    </div>
</template>

<script>
    import vSelect from './select.vue';

    export default {

        components: {
            vSelect,
        },

        props: {
            diet_meals: {
                required: true,
                type: Array,
            },
            value: {
                required: false,
                default: null,
            },
            error: {
                required: false,
                default: '',
                type: String,
            },
        },

        data() {
            return {
                //
            };
        },
    };
</script>