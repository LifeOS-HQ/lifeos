<template>

    <div class="card mb-3">
        <div class="card-header d-flex align-items-center">
            <div class="col px-0 pointer" @click="isShowing = ! isShowing">{{ model.name }}</div>
            <div class="btn-group btn-group-sm" role="group">
                <select v-model="form.filename" class="form-control form-control-sm" @change="show($event)">
                    <option v-for="(logfile, filename) in model.logfiles" :value="filename">{{ filename }}</option>
                </select>
            </div>
        </div>
        <div class="card-body" v-html="logfile" v-show="isShowing"></div>
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

        data() {

            var keys = Object.keys(this.model.logfiles),
                key = keys[keys.length - 1];

            return {
                form: {
                    filename: key,
                },
                isShowing: false,
                logfile: this.model.logfiles[key],
            };
        },

        methods: {
            show(event) {
                console.log(event);
                this.logfile = this.model.logfiles[this.form.filename];
            },
        },
    };
</script>