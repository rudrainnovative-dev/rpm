<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">All Tests
                    </h1>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="fs-6 text-link ">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">All Tests</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row mb-8">
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch w-100">
                            <div class="card-header border-0">
                                <div class="form-group my-3">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search Test..." class="form-control form-control-sm form-control-solid bg-white" v-model="search" v-on:keyup.enter="searchClick">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-light-dark btn-sm px-3 rounded-0 rounded-end" v-on:click="searchClick">
                                                <span class="svg-icon svg-icon-2 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect> <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path> <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path></g></svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-1">
                                    <router-link :to='{name:"TestAdd"}' class="btn btn-sm btn-primary">Create Test</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
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
                                        <ul class="dropdown-menu p-0 overflow-hidden dropdown-menu-end" aria-labelledby="" role="menu">
                                            <li>
                                                <router-link :to='{name:"TestEdit", params:{id: test.id}}' class="dropdown-item py-3 px-7">Edit</router-link>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-3 px-7" href="javascript:void(0)" v-on:click="duplicateTest(test.id)">Duplicate</a>
                                            </li>
                                            <li>
                                                <router-link :to='{name:"TestUserField", params:{id: test.id}}' class="dropdown-item py-3 px-7">Registration Fields for Candidate</router-link>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-3 px-7" href="javascript:void(0)" v-on:click="deleteTest(test.id)">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="separator"></div>
                            <div class="test-custom card-body">
                                <div class="test-content mt-5">
                                    <p class="sections">
                                       {{ countSection(test.test_questions) }} Sections
                                    </p>
                                    <p class="d-flex justify-content-between align-items-center border-bottom py-4 border-top">
                                        <span>{{ test.test_questions.length }} Questions</span>
                                        <router-link :to='{name:"TestQuestion", params:{id:test.id}}'><button class="btn btn-sm btn-light-dark px-3 text-center h-35px w-35px" type="button" v-tooltip="tooltip.show"><i class="p-0 fa fa-eye"></i></button></router-link>
                                    </p>
                                    <p class="d-flex justify-content-between align-items-center">
                                        <span>{{ test.duration }} Min</span>
                                        <router-link :to='{name:"OnlineTest", params:{id:test.public_id, user:"YWRtaW4="}}' target="_blank"><button class="btn btn-sm btn-light-dark px-3 text-center h-35px w-35px" type="button" v-tooltip="tooltip.link"><i class="p-0 fa fa-link"></i></button></router-link>
                                    </p>
                                </div>
                                <ul class="list-unstyled mb-0 mt-7 d-flex list-inline"> 
                                    <li class="list-inline-item w-50">
                                        <router-link :to='{name:"OnlineTestPreview", params:{id:test.public_id, user:"admin"}}' class="btn btn-light-dark btn-sm w-100" target="_blank">Preview Test</router-link>
                                    </li>
                                    <li class="list-inline-item w-50">
                                        <router-link :to='{name:"ReportList", params:{id:test.id}}' class="btn btn-light-dark btn-sm w-100">{{ test.taker_count }} Candidate(s)</router-link>
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

                    <div class="col-md-12 col-12 mt-5">
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
                            <div class="modal-header  px-5 py-3">
                                <h4>Duplicate Test</h4>
                                <div class="btn btn-sm btn-icon" data-bs-dismiss="modal">
                                    <span class="svg-icon svg-icon-1" v-on:click="closed">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-body p-5 pb-10">
                                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper" data-kt-stepper="true">
                                    <div class="row w-100 m-0">
                                    <div class="col-sm-12 px-0">
                                        <label>Test Name<small class="text-danger">*</small></label>
                                        <input type="text" class="form-control form-control-xl" v-model="duplicate.name" required />
                                    </div>
                                    <div class="col-sm-12 my-4 px-0">
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
            },
            search:''
        }
    },
    mounted(){
        this.getTests()
    },
    methods:{
        async getTests(page=1) {
          this.loader_spin = true
          Test.index(page, this.search).then(response => {
            this.tests = response.data.tests
            this.testsData = response.data.tests.data
            this.loader_spin = false
          })
          .catch(error=> {
            this.tests = []
            this.loader_spin = false
            if (error.response.status === 401) {
                this.$toast.error(error.response.data.message);
                this.$router.push({ name: "Login" });
            }
          });
        },
        deleteTest(id) {
            if(confirm("Are you sure to delete this test permanently?")){
                Test.delete(id).then(response => {
                    this.getTests()
                    this.$toast.success(response.data.message);
                }).catch(error=>{
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
                  
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
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
            })
        },
        async closed() {
            this.model_show = false
        },
        async searchClick() {
           this.getTests()
        },
        countSection(questions) {
            let arrayUniqueByKey = [...new Map(questions.map(item => [item['category_id'], item])).values()];
            return arrayUniqueByKey.length
        },
    }
}
</script>