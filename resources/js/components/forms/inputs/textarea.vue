<template>
    <div>
        <textarea
            class="form-control form-control-sm"
            :class="error ? 'is-invalid' : ''"
            :placeholder="placeholder"
            :readonly="readonly"
            :rows="rows"
            :value="value"
            @input="$emit('input', $event.target.value)">
        </textarea>
        <div class="invalid-feedback" v-text="error ? error : ''"></div>
    </div>
</template>

<script>
    export default {

        props: {
            error: {
                type: String,
            },
            label: {
                required: false,
                default: '',
            },
            placeholder: {
                required: false,
                default: '',
            },
            readonly: {
                required: false,
                default: false,
            },
            value: {
                required: false,
                default: '',
            },
            rows: {
                required: false,
                type: Number,
                default: 3,
            }
        },

    };
</script>
