<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Create Test</h1>    
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link small">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">Create Test</p>
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
                            <div class="card-header border-0">
                                <h3 class="card-title fw-bolder text-dark m-0">Create New Test</h3>
                            </div>
                            <div class="separator"></div>
                            <div class="card-body">
                                <form @submit.prevent="create">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="ques-name" class="mb-2 fw-bold">Test Name<span class="text-danger">*</span></label>
                                                <input type="test" class="form-control form-control-solid form-control-sm" v-model="test.name" placeholder="Enter Test Name" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                            <label for="ques-category" class="mb-2 fw-bold">Purpose of the Test<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-solid form-control-sm" v-model="test.purpose_id" placeholder="Purpose of test" required>
                                                    <option v-for="purpose in purposes" :value="purpose.id">{{ purpose.purpose }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12"></div>
                                        <div class="col-md-12 col-12 my-6">
                                            <div class="radio-inline">
                                                <label class="radio">
                                                    <input type="radio" v-model="test.assessment_type" :value="1" name="assessment_type" required>
                                                    <span></span>Time Assessment
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" v-model="test.assessment_type" :value="2" name="assessment_type" required>
                                                    <span></span> Deadline Based Assessment
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <ul class="list-unstyled list-inline m-0">
                                                <li class="list-inline-item">
                                                <button type="submit" class="btn btn-primary btn-sm" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Proceed</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </form>
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
</template>

<script>

    import Test from "../../apis/Test";
    import Testpurpose from "../../apis/Testpurpose";

    export default {
        name:"add-test",
        data(){
            return {
                 test: {
                    name: '',
                    purpose_id: '',
                    assessment_type: ''
                },
                purposes: [],
                disabled:false,
                loader_spin: true
            }
        },
        mounted() {
            this.getPurposes()
        },
        methods:{
            async create(){
                this.disabled=true
                Test.add(this.test).then(response => {
                    this.$router.push({name:"TestEdit", params:{id:response.data.test_id} });
                    this.disabled=false
                }).catch(error=> {
                    this.disabled=false
                })
            },
            async getPurposes() {
              Testpurpose.index().then(response => {
                this.purposes = response.data.purpose;
                this.loader_spin = false
              })
              .catch(error=> {
                this.purposes = []
                this.loader_spin = false
              });
            }
        }
    }
</script>