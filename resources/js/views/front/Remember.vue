<template>
    <div class="col-md-12 col-12 py-4">
        <div class="alert alert-info mb-8">
            Please read the instruction carefully before starting the test.
        </div>
        <div class="border border-secondary py-8 px-4 rounded">
            <ul>
                <li>Before start the test, please close all chat windows, screen-saver(s), etc and make sure that you have a stable internet connection.</li>
                <li>Pressing F5 at any time during the test will cause it to end immediately.</li>
                <li v-if="camera_enable">To ensure that your face will be clearly visible on the web camera during the exam.</li>
            </ul>
        </div>
        <div class="border border-danger text-danger mt-8 py-8 px-4 rounded" v-if="camera_error">
            <h4>Allow camera permission.</h4>
            <p v-if="camera_error == 'NotFoundError: Requested device not found'">Can't find camera in your machine. Please fixed the issue and refresh the page.</p>
            <p v-else>Click on the right side of address bar to allow camera. Then refresh the page.</p>
        </div>
        <div class="py-4 text-right">
            <button type="button" class="btn btn-sm btn-primary" v-on:click="proceedClick" v-if="!camera_error">Proceed</button>
        </div>
    </div>
</template>

<script>
    export default {
        name:"remember-online-test",
        props: ['camera_enable', 'camera_error'],
        methods: {
            async proceedClick() {
                this.$emit('childToParent', true);
            }
        }
    }
</script>