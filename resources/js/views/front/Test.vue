<template>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">
            <div class="row align-items-center position-relative py-2">
                <div class="col-md-3 col-12">
                    <div class="logo w-100">
                        <a href="javascript:void(0);" class="mt-5 pb-lg-0 pb-10 display-4 text-dark">RPM</a>
                    </div>
                </div>
                <div class="col-md-5 col-12 test-title position-md-absolute start-0 end-0 top-md-50 text-md-center mx-auto translate-md-middle-y">
                    <h3 class="card-title fw-bolder text-dark m-0"></h3>
                </div>
                <div class="col-md-4 col-12 position-md-absolute end-0 top-md-50 text-center mx-auto translate-md-middle-y">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <p class="m-0 pe-2"></p>
                        <p id="timer-custom" class="m-0 text-primary fw-bolder min-w-80px"></p>
                    </div>
                </div>
            </div>
            <div class="row" v-if="validateTest">
                <Remember v-if="proceed" />
                <div class="col-md-12 col-12 py-4 text-right" v-if="proceed">
                    <button type="button" class="btn btn-sm btn-primary" v-on:click="proceedClick">Proceed</button>
                </div>

                <div class="col-md-12 col-12 py-4" v-if="registation">
                    <Registation :registation_fields="registation_fields" :test_id="test.id" v-on:childToParent="onRegistationClick"/>
                </div>

            </div>
            <div class="row my-8" v-else>
                <div class="col-md-12 col-12 text-center">
                    <div class="border border-secondary p-8">
                        <span class="text-dark font-weight-blod">{{ test.errors.validate }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading_front" v-if="loader_spin">
            <div class="loader"></div>
        </div>
    </div>
</template>

<script>
    
    import Online from "../../apis/Online"
    import Remember from './Remember'
    import Registation from './Registation'

    export default {
        name:"online-test",
        components: { Remember, Registation },
        mounted() {
            this.getTest()
        },
        data() {
            return {
               proceed: false,
               validateTest: false,
               registation: false,
               loader_spin: false,
               registation_fields: '',
               test: {
                    id: '',
                    errors: {
                        validate: ''
                    }
               }
            }
        },
        methods:{
            async getTest() {
                this.loader_spin = true
                Online.index(this.$route.params.id).then(response => {
                    this.validateTest = true
                    this.proceed = true

                    const { id, name, registation_fields, duration } = response.data.test
                    this.registation_fields = registation_fields
                    
                    this.test.id = id
                    this.loader_spin = false
                }).catch(error=> {
                    if(error.response.status === 400) {
                        this.test.errors.validate = error.response.data.error
                    }
                    this.loader_spin = false
                })
            },
            async proceedClick() {
                this.proceed = false
                this.registation = true
            },
            async onRegistationClick(response) {
                console.log(response)
            }
        }
    }
</script>