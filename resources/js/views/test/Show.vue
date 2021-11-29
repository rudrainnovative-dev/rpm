<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Preview Test</h1>    
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link small">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">Preview Test</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch mb-xl-8 w-100">
                            <div class="card-body">
                                <div class="col-md-12 col-12 text-center">
                                    <span class="border text-dark d-inline-block py-2 px-4">{{ test.name }} - {{ test.purpose }}</span>
                                </div>
                                <div class="col-md-12 col-12 text-end">
                                    <span><span class="fa fa-clock text-muted"></span> Total {{ this.test.timerCount }}</span>
                                    <button type="button" class="btn btn-sm btn-primary ml-4">OK</button>
                                </div>
                                <div class="col-md-12 col-12 text-center">
                                    <button type="button" class="btn btn-sm btn-info ml-4" v-on:click="countDownTimer">Start</button>
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
        name:"edit-test",
        data(){
            return {
                disabled: false,
                loader_spin: true,
                test: {
                    name: '',
                    purpose: '',
                    timerCount: '00:00:00',
                },
            }
        },
        mounted() {
            this.getTest()
        },
        methods:{
            async getTest() {
                Online.index(this.$route.params.id).then(response => {

                    const { name, purpose, duration } = response.data.test

                    this.test.name = name
                    this.test.purpose = purpose.purpose
                    this.test.timerCount = duration*60
                    console.log(response)

                    this.loader_spin = false

                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async countDownTimer() {
                if(this.test.timerCount > 0) {
                    setTimeout(() => {
                        this.test.timerCount -= 1
                        this.countDownTimer()
                    }, 1000)
                }
            }
            
        }
    }
</script>