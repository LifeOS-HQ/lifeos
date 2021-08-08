<template>
    <select class="form-control form-control-sm" :value="value" @change="$emit('input', $event.target.value || null)">
        <option :value="null">{{ label }} auswählen</option>
        <option :value="option.id" v-for="(option, index) in options">{{ option.name }}</option>
    </select>
</template>

<script>
    export default {

        props: {
            label: {
                type: String,
                required: false,
                default: 'Datensatz',
            },
            options: {
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