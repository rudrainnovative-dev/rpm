<template>
    <div class="content d-flex flex-column flex-column-fluid">
        <div class="post d-flex flex-column-fluid">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch mb-xl-8 w-100">
                            <div class="card-header border-0">
                                <h3 class="card-title fw-bolder text-dark m-0">{{ test_name }}</h3>
                                <div class="d-flex align-items-center py-1">
                                    <div class="d-flex align-items-center">
                                        <p class="m-0 pe-2">Duration for Test:</p>
                                        <p class="m-0 text-primary fw-bolder min-w-75px">{{ timerCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="separator mb-2"></div>
                            <div class="card-body">
                                <div class="row my-8" v-if="!test_start">
                                    <div class="mw-400px w-100 mx-auto">
                                        <div class="mb-8 mb-lg-10">
                                            <h4 class="text-dark font-weight-normal mb-8">Before start the Test please enter the following details.</h4>
                                        </div>
                                        <form class="form fv-plugins-bootstrap fv-plugins-framework" v-if="registation_fields.length > 0" @submit.prevent="create">
                                            <div class="row">
                                                <div class="col-md-12 mb-2" v-for="field in registation_fields">
                                                    <div class="form-group" v-if="field.field == 'Name'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }} <span class="small text-danger" v-if="field.required">*</span></label>
                                                        <input class="form-control form-control-solid fw-normal px-0" :type="field.type" :placeholder="field.field" v-model="test_taker.name" autocomplete="off" :required="field.required">
                                                    </div>
                                                    <div class="form-group" v-if="field.field == 'Email'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }} <span class="small text-danger" v-if="field.required">*</span></label>
                                                        <input class="form-control form-control-solid fw-normal px-0" :type="field.type" :placeholder="field.field" v-model="test_taker.email" autocomplete="off" :required="field.required">
                                                    </div>
                                                    <div class="form-group" v-if="field.field == 'Phone Number'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }} <span class="small text-danger" v-if="field.required">*</span></label>
                                                        <input class="form-control form-control-solid fw-normal px-0" :type="field.type" :placeholder="field.field" v-model="test_taker.mobile" autocomplete="off" :required="field.required">
                                                    </div>
                                                    <div class="form-group" v-if="field.field == 'Date of Birth'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }} <span class="small text-danger" v-if="field.required">*</span></label>
                                                        <input class="form-control form-control-solid fw-normal px-0" :type="field.type" :placeholder="field.field" v-model="test_taker.dob" autocomplete="off" :required="field.required">
                                                    </div>
                                                    <div class="form-group" v-if="field.field == 'Gender'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }}</label>
                                                        <select class="form-control form-control-solid fw-normal"  v-model="test_taker.gender" :required="field.required">
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group" v-if="field.field == 'Profile Picture'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }} <span class="small text-danger" v-if="field.required">*</span></label>
                                                        <input class="form-control form-control-solid fw-normal px-0" :type="field.type" :placeholder="field.field" v-model="test_taker.avatar" autocomplete="off" :required="field.required">
                                                    </div>
                                                    <div class="form-group" v-if="field.field == 'Identity card'">
                                                        <label class="w-100 mb-2 fw-bold">{{ field.field }} <span class="small text-danger" v-if="field.required">*</span></label>
                                                        <input class="form-control form-control-solid fw-normal px-0" :type="field.type" :placeholder="field.field" v-model="test_taker.id_card" autocomplete="off" :required="field.required">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                                        <button type="submit" class="btn btn-primary font-weight-bold">Start Test</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loading" v-if="loader_spin">
                <div class="loader"></div>
            </div>
        </div>
    </div>
</template>

<script>
    
    import Online from "../../apis/Online";

    export default {
        name:"online-test",
        data(){
            return {
                disabled: false,
                loader_spin: true,
                test_id: '', 
                test_name: '',
                duration: '',
                timerCount: '',
                registation_fields: [],
                test_taker: {
                    name: "",
                    email: '',
                    dob: '',
                    mobile: '',
                    gender: '',
                    avatar: '',
                    id_card: ''
                },
                test_start: false,
            }
        },
        mounted() {
            this.getTest()
        },
        methods:{
            async getTest() {
                Online.index(this.$route.params.id).then(response => {

                    const {id, name, duration} = response.data.test
                    if(localStorage.test_taker_test_id == id) {
                        this.test_start = true
                        this.countDownStart()
                    }
                    
                    this.test_id = id
                    this.test_name = name
                    this.registation_fields = response.data.test.registation_fields
                    this.duration = duration
                    this.timerCount = this.convertHHMMSS(duration)


                    console.log(response)

                    this.loader_spin = false

                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async create() {
                this.disabled=true
                Online.register(this.test_taker, this.test_id).then(response => {
                    if(response.data.success) {
                      localStorage.setItem("test_taker", response.data.taker.email)
                      localStorage.setItem("test_taker_test_id", response.data.taker.test_id)
                      this.test_start = true
                      this.countDownStart()
                    }
                    this.disabled=false
                }).catch(error=> {
                    this.disabled=false
                    if (error.response.status === 422) {
                       this.$toast.error(error.response.data.errors);
                    }

                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                    }
                })
            },
            async countDownStart() {
                if(this.duration > 0) {
                    setTimeout(() => {
                        this.duration -= 1
                        this.convertHHMMSS(this.duration)
                    }, 1000)
                }
            },
            convertHHMMSS(min) {
                var seconds = min*60
                var convert = function(x) { return (x < 10) ? "0"+x : x; }

                return convert(parseInt(seconds / (60*60))) + ":" + convert(parseInt(seconds / 60 % 60)) + ":" + convert(seconds % 60)
            }
            
            
        }
    }
</script>