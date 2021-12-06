<template>
    <div class="row">
        <div class="col-md-12 col-12">
            <div class="alert alert-info mb-8"><span>Just few more steps to begin you test...</span></div>
            <div class="card">
                <div class="card-header">
                    <div class="text-muted"><span class="text-danger">*</span> Indicates required field</div>
                </div>
                <div class="card-body">
                    <form @submit.prevent="registationSubmit">
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="mb-2 fw-bold">Name<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-solid h-auto py-2 px-2 fw-normal" type="text" v-model="candidate.name" required/>
                                </div>
                                <div class="form-group">
                                    <label class="mb-2 fw-bold">Email<span class="text-danger">*</span></label>
                                    <input class="form-control form-control-solid" type="email" v-model="candidate.email" required/>
                                </div>
                                <div class="form-group" v-if="field.field == 'Phone Number'" v-for="field in registation_fields">
                                    <label class="mb-2 fw-bold">{{ field.field }}<small class="text-danger" v-if="field.required">*</small></label>
                                    <input :type="field.type" class="form-control form-control-solid h-auto py-2 px-2 fw-normal" :placeholder="field.field" autocomplete="off" :required="(field.required)?true:false" v-model="candidate.mobile"/>
                                </div>
                                <div class="form-group" v-if="field.field == 'Date of Birth'" v-for="field in registation_fields">
                                    <label class="mb-2 fw-bold">{{ field.field }}<small class="text-danger" v-if="field.required">*</small></label>
                                    <input :type="field.type" class="form-control form-control-solid h-auto py-2 px-2 fw-normal" :placeholder="field.field" autocomplete="off" :required="(field.required)?true:false" v-model="candidate.dob"/>
                                </div>
                                <div class="form-group" v-if="field.field == 'Gender'" v-for="field in registation_fields">
                                    <label class="mb-2 fw-bold">{{ field.field }}<small class="text-danger" v-if="field.required">*</small></label>
                                    <select class="form-control form-control-solid h-auto py-2 px-2 fw-normal" :required="(field.required)?true:false" v-model="candidate.gender">
                                        <option value="Mail">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="ml-4 col-md-4 col-12">
                                <div class="row">
                                    <div class="col-md-6 col-6">
                                        <div class="form-group" v-if="field.field == 'Profile Picture'" v-for="field in registation_fields">
                                            <label class="mb-2 fw-bold">{{ field.field }}<small class="text-danger" v-if="field.required">*</small></label>
                                            <div class="form-group">
                                                <input type="radio" name="radio_image" id="avatarImage" v-model="radio_image" value="avatar" style="position: absolute;z-index: -1;"/>
                                                <label for="avatarImage"> 
                                                    <img :src="avatar" class="p-1" :class="(radio_image == 'avatar')?'border border-info':'border border-secondary'" style="width:100%">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-6">
                                        <div class="form-group" v-if="field.field == 'Identity card'" v-for="field in registation_fields">
                                            <label class="mb-2 fw-bold">{{ field.field }}<small class="text-danger" v-if="field.required">*</small></label>
                                            <div class="form-group">
                                                <input type="radio" name="radio_image" id="aadhaarImage" v-model="radio_image" value="id_card" style="position: absolute;z-index: -1;"/>
                                                <label for="aadhaarImage"> 
                                                    <img :src="id_card" class="p-1" :class="(radio_image == 'id_card')?'border border-info':'border border-secondary'" style="width:100%">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div style="height: 140px" class="border p-2 text-center">
                                            <span class="text-info d-block" v-if="!radio_image">Please select Profile picture or Identity Card.</span>
                                            <video ref="camera" :width="canvasWidth" :height="canvasHeight" autoplay></video>
                                            <canvas v-show="false" ref="canvas" :width="canvasWidth" :height="canvasHeight"></canvas>
                                            <span class="text-danger d-block" v-if="webcame_message">{{ webcame_message }}</span>
                                        </div>
                                        <div class="text-center my-4" v-if="!webcame_message">
                                            <button type="button" class="btn btn-sm btn-secondary mb-8" v-on:click="captureImage">Capture</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-12 text-right">
                                <div class="form-group mt-5">
                                    <button type="submit" class="btn btn-primary btn-sm" :disabled="disabled">Next</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="loading_front" v-if="loader_spin">
            <div class="loader"></div>
        </div>
    </div>
</template>

<script>

    export default {
        name:"register-online-test",
        props: ['registation_fields', 'test_id'],
        data() {
            return {
                loader_spin: true,
                avatar: '../../img/profile.png',
                id_card: '../../img/aadhaar-card.png',
                radio_image: false,
                disabled: false,
                candidate: {
                    name: '',
                    email: '',
                    gender: '',
                    mobile: '',
                    dob:'',
                    test_id: '',
                    avatar:'',
                    id_card:'',
                },
                canvasWidth:200,
                canvasHeight:114,
                webcame_message:''
            }
        },
        created: function () {
            this.loader_spin = false
        },
        mounted() {
            this.startCameraStream()
        },
        methods: {
            async captureImage() {
                if(this.radio_image) {
                    const FLASH_TIMEOUT = 50;
                    let self = this;
                    setTimeout(() => {
                        const context = self.$refs.canvas.getContext('2d');
                        context.drawImage(self.$refs.camera, 0, 0, self.canvasWidth, self.canvasHeight);
                        const dataUrl = self.$refs.canvas.toDataURL("image/jpeg")
                            .replace("image/jpeg", "image/octet-stream");

                        if(this.radio_image == 'avatar') {
                            this.candidate.avatar = dataUrl
                            this.avatar = dataUrl
                        }
                        else {
                            this.candidate.id_card = dataUrl
                            this.id_card = dataUrl
                        }
                    }, FLASH_TIMEOUT);
                }
            },
            async startCameraStream() {
                const constraints = (window.constraints = {
                    audio: false,
                    video: { facingMode: { exact: "user" }, sharpness: true, pan: true, focusMode: true }
                });
                navigator.mediaDevices
                .getUserMedia(constraints)
                .then(stream => {
                    this.$refs.camera.srcObject = stream;
                    this.disabled = true
                }).catch(error => {
                    this.webcame_message = error
                    this.disabled = true
                });
            },
            async stopCameraStream() {
                let tracks = this.$refs.camera.srcObject.getTracks();
                tracks.forEach(track => {
                    track.stop();
                });
            },
            async registationSubmit() {
                this.disabled=true
                Online.register(this.candidate, this.test_id).then(response => {
                    
                }).catch(error=> {
                    this.disabled=false
                    if (error.response.status === 422) {
                       this.$toast.error(error.response.data.errors);
                    }
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.error);
                    }
                })
            }
        }
    }
</script>