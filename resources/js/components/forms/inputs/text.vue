<template>
    <div>
        <input class="form-control form-control-sm" :class="error ? 'is-invalid' : ''" type="text" :placeholder="placeholder" :readonly="readonly" :value="value" @input="$emit('input', $event.target.value)" @keydown.enter="$emit('keydown', $event)">
        <div class="invalid-feedback" v-text="error ? error : ''"></div>
    </div>
</template>

<script>
    export default {

        props: {
            error: {
                type: String,
            },
            label: {
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
        },

    };
</script>