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
                                <h3 class="card-title fw-bolder text-dark m-0"></h3>
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
                                <div class="col-md-12 col-12">
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
                                            <input type="file" ref="excel_file" class="form-control form-control-solid form-control-sm" v-on:change="onFileChange($event)" required/>
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-sm btn-primary" value="Upload"/>
                                            <a href="/assets/excel/question_excel.xlsx" class="text-success float-right">Download sample excel</a>
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
            model_show: false,
            excel_file: '',
            tooltip: {
                show: 'Show',
                edit: 'Edit',
                delete: 'Delete'
            }
        }
    },
    mounted(){
        this.getQuestions()
    },
    methods:{
        async getQuestions(page = 1) {
            this.loader_spin = true
            Question.index(page).then(response => {
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
            
            let fileData = new FormData();
            fileData.append('excel_file', this.excel_file);
            Question.uploadExcel(fileData).then(response => {
                console.log(response)
            }).catch(error=>{
                 
            });
        }
    }
}
</script>