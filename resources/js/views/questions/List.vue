<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Question Bank</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">Question Bank</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch w-100">
                            <div class="card-header border-0">
                                <div class="form-group my-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm form-control-solid bg-white" v-model="search" placeholder="Search Question...">
                                        <div class="input-group-append">
                                            <button class="btn btn-light-dark btn-sm px-3 rounded-0 rounded-end" type="button" v-on:click="searchClick">
                                                <span class="svg-icon svg-icon-2 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                            <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-1">
                                    <router-link :to='{name:"QuestionAdd"}' class="btn btn-sm btn-light me-4">Add Question</router-link>
                                    <button type="button" class="btn btn-sm btn-flex btn-primary btn-active-primary" v-on:click="OpenModel">Upload Excel</button>
                                </div>
                            </div>
                            <div class="separator mb-2"></div>
                            <div class="card-body">
                                <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th class="fw-bolder align-middle">Category</th>
                                            <th class="fw-bolder align-middle">Question Name</th>
                                            <th class="fw-bolder align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="questionsData.length > 0">
                                        <tr v-for="(question,key) in questionsData" :key="key">
                                            <td class="align-middle">{{ question.category.name }}</td>
                                            <td class="align-middle">{{ question.title }}</td>
                                            <td class="align-middle action-td">
                                                <ul class="list-unstyled list-inline m-0">
                                                    <li class="list-inline-item mb-2" title="View" v-tooltip="tooltip.show">
                                                        <router-link :to='{name:"QuestionShow",params:{id:question.id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button"><i class="p-0 fa fa-eye"></i></button>
                                                        </router-link>
                                                    </li>
                                                    <li class="list-inline-item mb-2" title="Edit" v-tooltip="tooltip.edit">
                                                        <router-link :to='{name:"QuestionEdit",params:{id:question.id}}'><button class="btn btn-sm btn-light-warning p-0 text-center h-30px w-30px" type="button"><i class="p-0 fa fa-edit"></i></button></router-link>
                                                    </li>
                                                    <li class="list-inline-item mb-2" title="Delete" v-tooltip="tooltip.delete">
                                                        <button class="btn btn-sm btn-light-danger p-0 text-center h-30px w-30px" type="button" @click="deleteQuestion(question.id)"><i class="p-0 fa fa-trash"></i></button>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-if="questionsData.length == 0">
                                        <tr>
                                            <td colspan = '3'>No any question.</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12 col-12 mt-5">
                                    <div class="d-flex justify-content-end align-items-center flex-wrap">
                                        <pagination :data="questions" :limit="2" @pagination-change-page="getQuestions"></pagination>
                                    </div>
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

        <div v-if="model_show">
            <div class="modal fade show" id="kt_modal_create_app" tabindex="-1" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Upload Question</h2>
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
                                <form @submit.prevent="updateExcel" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group">
                                            <label class="mb-2 fw-bold">Upload Excel<span class="text-danger">*</span></label>
                                            <input type="file" ref="excel_file" class="form-control form-control-solid form-control-sm" v-on:change="onFileChange($event)" accept=".xls,.xlsx" required/>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-primary" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Upload</button>
                                            <a href="/assets/excel/sample_questions.xlsx" class="text-info float-right">Download sample file</a>
                                        </div>
                                    </div>
                                </form>
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
import Question from "../../apis/Question";

export default {
    name:"questions",
    data() {
        return {
            questions:{},
            questionsData: [],
            loader_spin: false,
            disabled: false,
            model_show: false,
            excel_file: '',
            tooltip: {
                show: 'Show',
                edit: 'Edit',
                delete: 'Delete'
            },
            error: '',
            search: ''
        }
    },
    mounted(){
        this.getQuestions()
    },
    methods:{
        async getQuestions(page = 1) {
            this.loader_spin = true
            Question.index(page, this.search).then(response => {
                this.questions = response.data.questions;
                this.questionsData = response.data.questions.data;
                this.loader_spin = false
            })
            .catch(error=> {
                this.questions = []
                this.loader_spin = false
            });
        },
        async deleteQuestion(id) {
            if(confirm("Are you sure to delete this question ?")){
                Question.delete(id).then(response => {
                    this.getQuestions()
                    this.$toast.success(response.data.message);
                }).catch(error=>{
                    this.loader_spin = false 
                });
            }
        },
        async OpenModel() {
            this.model_show = true
        },
        async closed() {
            this.model_show = false
        },
        async onFileChange(event) {
            this.excel_file = event.target.files[0]
        },
        async updateExcel() {
            this.disabled = true
            let fileData = new FormData();
            fileData.append('excel_file', this.excel_file);
            Question.uploadExcel(fileData).then(response => {
                this.$toast.success(response.data.message)
                this.model_show = false
                this.excel_file = ''
                this.getQuestions()
                this.disabled = false
            }).catch(error=>{
                if (error.response && error.response.status === 400) {
                  this.$toast.error(error.response.data.message)
                }
                this.disabled = false
            });
        },
        async searchClick(){
            this.getQuestions()
        }
    }
}
</script>