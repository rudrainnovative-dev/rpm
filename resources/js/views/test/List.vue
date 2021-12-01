<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">All Tests
                    </h1>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">All Tests</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-12 col-12 d-flex mb-md-4 mb-4 justify-content-end">
                        <div class="d-flex align-items-center py-1">
                            <router-link :to='{name:"TestAdd"}' class="btn btn-sm btn-primary">Create Test</router-link>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mb-lg-8 mb-5" v-for="test in testsData" v-if="testsData.length > 0">
                        <div class="card card-xl-stretch w-100">
                            <div class="card-header border-0">
                                <h3 class="card-title align-items-start flex-column my-3">
                                    <span class="fw-bolder text-dark">{{ test.name }}</span>
                                    <span class="text-muted mt-3 font-weight-normal small">{{ test.purpose.purpose }}</span>
                                </h3>
                                <div class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-default px-0 my-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                                            </svg>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="" role="menu">
                                            <li>
                                                <router-link :to='{name:"TestEdit", params:{id: test.id}}' class="dropdown-item">Edit</router-link>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" v-on:click="duplicateTest(test.id)">Duplicate</a>
                                            </li>
                                            <li>
                                                <router-link :to='{name:"TestUserField", params:{id: test.id}}' class="dropdown-item">Registration Fields for Candidate</router-link>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" v-on:click="deleteTest(test.id)">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="separator"></div>
                            <div class="test-custom card-body">
                                <div class="test-content mt-5">
                                    <p class="sections">
                                       4 Sections
                                    </p>
                                    <p class="d-flex justify-content-between align-items-center border-bottom py-4 border-top">
                                        <span>{{ test.test_questions.length }} Questions</span>
                                        <router-link :to='{name:"TestQuestion", params:{id:test.id}}'><button class="btn btn-sm btn-light-dark px-3 text-center h-35px w-35px" type="button" v-tooltip="tooltip.show"><i class="p-0 fa fa-eye"></i></button></router-link>
                                    </p>
                                    <p class="d-flex justify-content-between align-items-center">
                                        <span>{{ test.duration }} Min</span>
                                        <router-link :to='{name:"OnlineTest", params:{id:test.public_id}}'><button class="btn btn-sm btn-light-dark px-3 text-center h-35px w-35px" type="button" v-tooltip="tooltip.link"><i class="p-0 fa fa-link"></i></button></router-link>
                                    </p>
                                </div>
                                <ul class="list-unstyled mb-0 mt-7 d-flex list-inline"> 
                                    <li class="list-inline-item w-50">
                                        <router-link :to='{name:"OnlineTest", params:{id:test.public_id}}' class="btn btn-light-dark btn-sm w-100">Preview Test</router-link>
                                    </li>
                                    <li class="list-inline-item w-50">
                                        <button class="btn btn-light-dark btn-sm w-100">0 Candidate(s)</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12 mb-lg-8 mb-5" v-if="testsData.length == 0">
                        <div class="card card-xl-stretch w-100">
                            <div class="test-custom card-body">
                                <span>No any test found.</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-12">
                        <div class="d-flex justify-content-end align-items-center flex-wrap">
                            <pagination :data="tests" :limit="2" @pagination-change-page="getTests"></pagination>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
        <div class="loading" v-if="loader_spin">
            <div class="loader"></div>
        </div>

        <div v-if="model_show">
                <div class="modal fade show" id="kt_modal_create_app" tabindex="-1" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2>Duplicate Test</h2>
                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                    <span class="svg-icon svg-icon-1" v-on:click="closed">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-body py-lg-10 px-lg-10">
                                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper" data-kt-stepper="true">
                                    <div class="row">
                                    <div class="col-sm-12">
                                        <label>Test Name<small class="text-danger">*</small></label>
                                        <input type="text" class="form-control form-control-xl" v-model="duplicate.name" required />
                                    </div>
                                    <div class="col-sm-12 my-4">
                                        <button class="btn btn-primary btn-sm" :disabled="disabled" v-on:click="DuplicateModel(0)"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        </div>
    </div>
</template>

<script>

import Test from "../../apis/Test";

export default {
    name:"tests",
    data(){
        return {
            tests:{},
            testsData: [],
            loader_spin: true,
            tooltip: {
                show: 'Show',
                edit: 'Edit',
                delete: 'Delete',
                link: 'Public'
            },
            model_show: false,
            disabled: false,
            duplicate: {
                name: '',
                id: ''
            }
        }
    },
    mounted(){
        this.getTests()
    },
    methods:{
        async getTests(page=1) {
          Test.index(page).then(response => {
            this.tests = response.data.tests
            this.testsData = response.data.tests.data
            this.loader_spin = false
          })
          .catch(error=> {
            this.tests = []
            this.loader_spin = false
          });
        },
        deleteTest(id) {
            if(confirm("Are you sure to delete this test permanently?")){
                Test.delete(id).then(response => {
                    this.getTests()
                    this.$toast.success(response.data.message);
                }).catch(error=>{
                  
                })
            }
        },
        async duplicateTest(id) {
            this.duplicate.id = id
            this.model_show = true
        },
        async DuplicateModel() {
            
            this.loader_spin = true
            this.model_show = false

            Test.duplicateTest(this.duplicate).then(response => {
                this.getTests()
                this.loader_spin = false
                this.$toast.success(response.data.message);
            }).catch(error=>{
                this.loader_spin = true
            })
        },
        async closed() {
            this.model_show = false
        }

    }
}
</script>