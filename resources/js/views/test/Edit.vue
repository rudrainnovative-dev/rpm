<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Edit Test</h1>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-6">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link fs-6">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Edit Test</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <form @submit.prevent="update">
                    <div class="row">
                      <div class="col-md-12 col-12">
                          <div class="d-flex align-items-center justify-content-md-end mb-3 mt-3">
                              <router-link :to='{name:"Test"}' class="btn btn-sm btn-secondary">Back to List</router-link>
                          </div>
                      </div>
                        <div class="col-md-12 col-12 d-flex mb-5">
                            <div class="card card-xl-stretch mb-xl-1 w-100">
                                <div class="card-header border-0">
                                    <h3 class="card-title fw-bolder text-dark m-0">&nbsp;</h3>

                                        <ul class="list-unstyled list-inline mt-3 mb-0 text-md-end">
                                            <li class="list-inline-item">
                                                <router-link :to='{name:"TestAdd"}' class="btn btn-primary btn-sm">Create Test</router-link>
                                            </li>
                                            <li class="list-inline-item">
                                                <router-link :to='{name:"OnlineTestPreview", params:{id:public_id, user:"admin"}}' class="btn btn-dark btn-sm w-100" target="_blank">Preview Test</router-link>
                                            </li>
                                        </ul>

                                </div>

                                <div class="separator"></div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="mb-3 fw-bolder fs-5">Test Name<span class="text-danger">*</span></label>
                                                <input type="test" class="form-control form-control-solid form-control-sm" v-model="test.name" placeholder="Enter Test Name" required/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="mb-3 fw-bolder fs-5">Purpose of the Test<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-solid form-control-sm status-filter" v-model="test.purpose_id" placeholder="Purpose of test" required>
                                                    <option v-for="purpose in purposes" :value="purpose.id">{{ purpose.purpose }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12"></div>
                                        <div class="col-md-12 col-12 my-6">
                                            <div class="radio-inline">
                                                <label class="radio me-6">
                                                    <input type="radio" v-model="test.assessment_type" :value="1" name="assessment_type" required>
                                                    <span class="me-2"></span>Time Assessment
                                                </label>
                                                <label class="radio">
                                                    <input type="radio" v-model="test.assessment_type" :value="2" name="assessment_type" required>
                                                    <span class="me-2"></span> Deadline Based Assessment
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5" v-if="test.assessment_type == 1">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label class="me-2 w-100 fw-bold">Test Duration (min)<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-solid form-control-sm" v-model="test.duration" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 d-flex mb-5">
                            <div class="card card-xl-stretch mb-xl-8 w-100">

                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label class="mb-3 fw-bolder fs-5">Search & Add Skill to the Test<span class="text-danger">*</span></label>
                                                    <select class="form-control form-control-solid form-control-sm status-filter" v-model="selected_skills" v-on:change="SelectedSkills($event)">
                                                        <option :value="1">Select Question From Other Test</option>
                                                        <option :value="2">Select Question From Question Bank</option>
                                                        <option :value="3">Add Question</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12"></div>
                                            <div class="col-md-4 col-12"></div>
                                            <div class="col-md-4 col-12 mt-8">
                                                <div class="p-6 bg-light-primary rounded">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <rect fill="#000000" opacity="0.3" x="4" y="5" width="16" height="2" rx="1"/>
                                                                <rect fill="#000000" opacity="0.3" x="4" y="13" width="16" height="2" rx="1"/>
                                                                <path d="M5,9 L13,9 C13.5522847,9 14,9.44771525 14,10 C14,10.5522847 13.5522847,11 13,11 L5,11 C4.44771525,11 4,10.5522847 4,10 C4,9.44771525 4.44771525,9 5,9 Z M5,17 L13,17 C13.5522847,17 14,17.4477153 14,18 C14,18.5522847 13.5522847,19 13,19 L5,19 C4.44771525,19 4,18.5522847 4,18 C4,17.4477153 4.44771525,17 5,17 Z" fill="#000000"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    <p class="mt-4 mb-0">No. of Sections</p>
                                                    <h4 class="display-4 fw-bolder">{{ total_sections }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mt-8">
                                                <div class="p-6 bg-light-warning rounded">
                                                    <span class="svg-icon svg-icon-warning svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                                                <path d="M12,16 C12.5522847,16 13,16.4477153 13,17 C13,17.5522847 12.5522847,18 12,18 C11.4477153,18 11,17.5522847 11,17 C11,16.4477153 11.4477153,16 12,16 Z M10.591,14.868 L10.591,13.209 L11.851,13.209 C13.447,13.209 14.602,11.991 14.602,10.395 C14.602,8.799 13.447,7.581 11.851,7.581 C10.234,7.581 9.121,8.799 9.121,10.395 L7.336,10.395 C7.336,7.875 9.31,5.922 11.851,5.922 C14.392,5.922 16.387,7.875 16.387,10.395 C16.387,12.915 14.392,14.868 11.851,14.868 L10.591,14.868 Z" fill="#000000"/>
                                                            </g>
                                                        </svg>
                                                    </span>

                                                    <p class="mt-4 mb-0">No. of Questions</p>
                                                    <h4 class="display-4 fw-bolder">{{ total_questions }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 mt-8">
                                                <div class="p-6 bg-light-info rounded">
                                                    <span class="svg-icon svg-icon-info svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M16.0322024,5.68722152 L5.75790403,15.945742 C5.12139076,16.5812778 5.12059836,17.6124773 5.75613416,18.2489906 C5.75642891,18.2492858 5.75672377,18.2495809 5.75701875,18.2498759 L5.75701875,18.2498759 C6.39304347,18.8859006 7.42424328,18.8859006 8.060268,18.2498759 C8.06056298,18.2495809 8.06085784,18.2492858 8.0611526,18.2489906 L18.3196731,7.9746922 C18.9505124,7.34288268 18.9501191,6.31942463 18.3187946,5.68810005 L18.3187946,5.68810005 C17.68747,5.05677547 16.6640119,5.05638225 16.0322024,5.68722152 Z" fill="#000000" fill-rule="nonzero"/>
                                                                <path d="M9.85714286,6.92857143 C9.85714286,8.54730513 8.5469533,9.85714286 6.93006028,9.85714286 C5.31316726,9.85714286 4,8.54730513 4,6.92857143 C4,5.30983773 5.31316726,4 6.93006028,4 C8.5469533,4 9.85714286,5.30983773 9.85714286,6.92857143 Z M20,17.0714286 C20,18.6901623 18.6898104,20 17.0729174,20 C15.4560244,20 14.1428571,18.6901623 14.1428571,17.0714286 C14.1428571,15.4497247 15.4560244,14.1428571 17.0729174,14.1428571 C18.6898104,14.1428571 20,15.4497247 20,17.0714286 Z" fill="#000000" opacity="0.3"/>
                                                            </g>
                                                        </svg>
                                                    </span>

                                                    <p class="mt-4 mb-0">Total Marks</p>
                                                    <h4 class="display-4 fw-bolder">{{ total_marks }}</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-12 my-8" id="selected_question_div">
                                            <div class="table table-rounded">
                                                <table class="table-striped border gy-7 gs-7 m-0 w-100">
                                                    <thead>
                                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                            <th class="fw-bolder align-middle">Section Name</th>
                                                            <th class="fw-bolder align-middle">No. of Question</th>
                                                            <th class="fw-bolder align-middle">Correct Grade</th>
                                                            <th class="fw-bolder align-middle">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="table_questions.length > 0">
                                                       <tr v-for="(item,key) in table_questions" :key="key">
                                                            <td class="align-middle">{{ item.category }}</td>
                                                            <td class="align-middle">{{ key+1 }} out of {{ table_questions.length }}</td>
                                                            <td class="align-middle">{{ item.marks }}</td>
                                                            <td class="align-middle">
                                                                <ul class="list-unstyled list-inline m-0">
                                                                    <li class="list-inline-item mb-2">
                                                                        <router-link :to='{name:"QuestionShow", params: {id: item.question_id}}' class="text-link small"><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" v-tooltip="tooltip.show"><i class="p-0 fa fa-eye"></i></button></router-link>
                                                                    </li>
                                                                    <li class="list-inline-item mb-2">
                                                                        <button class="btn btn-sm btn-light-danger p-0 text-center h-30px w-30px" type="button" v-on:click="deleteQuestionFromTest(key)" v-tooltip="tooltip.delete"><i class="p-0 fa fa-trash"></i></button>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                       </tr>
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-12 col-12">
                                                <nav>
                                                    <ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line border-0" id="nav-tab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link show fw-bold h5 px-4 py-3 active fs-6" id="test-setting-tab" data-bs-toggle="tab" href="#test-setting" role="tab" aria-controls="test-setting" aria-selected="false">Test Settings</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link fw-bold fw-bold h5 px-4 py-3 fs-6" id="section-setting-tab" data-bs-toggle="tab" href="#section-setting" role="tab" aria-controls="section-setting" aria-selected="false">Section Settings</a>
                                                        </li>
                                                    </ul>
                                                </nav>
                                                <div class="tab-content border p-7 rounded-0 rounded-bottom rounded-end" id="nav-tabContent" style="margin-top:-1px">
                                                    <div class="tab-pane fade active show" id="test-setting" role="tabpanel" aria-labelledby="test-setting-tab">
                                                        <div class="form-group checkbox-list mt-1" v-for="(criteria,key) in criterias" :key="key">
                                                            <label class="checkbox">
                                                                <input class="me-2" type="checkbox" v-model="test.test_settings" :value="criteria.id">
                                                                <span></span> {{ criteria.name }}
                                                            </label>
                                                            <textarea class="form-control form-control-solid form-control-sm" v-model="test.start_message" v-if="test.test_settings.includes(6) && criteria.id == 6" required placeholder="Custom message before starting the test"></textarea>
                                                            <textarea class="form-control form-control-solid form-control-sm" v-model="test.end_message" v-if="test.test_settings.includes(8) && criteria.id == 8" required  placeholder="Custom message for the candidates after test finishes"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="section-setting" role="tabpanel" aria-labelledby="section-setting-tab">
                                                        <div class="row mt-3">
                                                            <div class="col-md-2 py-3">
                                                                <label class="mb-3 fw-bolder fs-5">Select Section</label>
                                                            </div>
                                                            <div class="col-md-4 col-12">
                                                                <div class="form-group">
                                                                    <select class="form-control form-control-solid form-control-sm status-filter" v-model="select_selection">
                                                                        <option :value="category.id" v-for="category in categories">{{ category.name }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-5">
                                                            <div class="col-md-12" v-for="cat_id in categories" :class="(select_selection == cat_id.id)?'d-block':'d-none'">
                                                                <label class="mb-3 fw-bolder fs-5">Section Instructions</label><br>
                                                                <textarea class="form-control form-control-solid form-control-sm" rows="3" v-model="test.section_settings[cat_id.id]" placeholder="Remarks"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-5 align-items-center">
                                                            <div class="col-md-4">
                                                                <label class="fw-bolder fs-5">Section Name</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="fw-bolder fs-5">Order</label>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                        <div class="row mt-3 align-items-center" v-for="category in categories">
                                                            <div class="col-md-4" >
                                                                <span>{{ category.name }}</span>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" min="0" class="form-control form-control-solid form-control-sm w-50" v-model="test.order_settings[category.id]">
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5 mb-3">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="loading" v-if="loader_spin">
                <div class="loader"></div>
            </div>
            <div v-if="model_show">
                <div class="modal fade show" id="kt_modal_create_app" tabindex="-1" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header px-5 py-3">
                                <h4 v-if="selected_skills == 1">Select Question From Other Test</h4>
                                <h4 v-if="selected_skills == 2">Select Question From Question Bank</h4>
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
                                    <div class="col-sm-12 w-100 px-0" v-if="selected_skills == 1">
                                        <label class="mb-3 fw-bolder fs-5">Select Test</label>
                                        <select class="form-control form-control-solid form-control-sm" v-model="select_test_question" v-on:change="GetSelectedTestQuestion($event)">
                                            <option v-for="test in test_list" :value="test.id">{{ test.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 px-0" v-if="selected_skills == 2">
                                        <label class="mb-3 fw-bolder fs-5">Select Category</label>
                                        <select class="form-control form-control-solid form-control-sm status-filter" v-model="select_category_question" v-on:change="GetSelectedCategoryQuestion($event)">
                                            <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 mt-5 px-0" id="min_height_model_div">
                                    <div class="table table-rounded border">
                                        <table class="table table-striped gy-7 gs-7 m-0 w-100">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Question Name</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="question_list.length > 0">
                                                <tr v-for="item in question_list">
                                                    <td class="align-middle">
                                                        <div class="form-group checkbox-list mt-3">
                                                            <label class="checkbox">
                                                                <input class="me-2" type="checkbox" v-model="test.selected_question" :value="{'question_id': item.id, 'category_id': item.category_id, 'category': item.category.name, 'marks': item.marks}">
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle" v-html="item.title"></td>
                                                </tr>
                                            </tbody>
                                            <tbody v-if="question_list.length == 0">
                                                <tr>
                                                    <td colspan="2" class="align-middle">No Question(s) found.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <span class="d-block text-primary mb-4" v-if="test.selected_question.length > 0">{{ test.selected_question.length }} Question(s) Selected.</span>
                                        <span class="d-block text-success mb-4" v-if="wait_enable">Please wait ...</span>
                                    </div>
                                    <div class="col-sm-12" v-if="test.selected_question.length > 0">
                                        <button class="btn btn-primary btn-sm" :disabled="disabled" v-on:click="DoneModel(0)"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Done</button>
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
    import Testpurpose from "../../apis/Testpurpose";
    import Question from "../../apis/Question";
    import Category from "../../apis/Category";
    import moment from 'moment'
    import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
    import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';


    export default {
        name:"edit-test",
        components: { VueCtkDateTimePicker },
        data(){
            return {
                disabled: false,
                loader_spin: true,
                test: {
                    _method:"patch",
                    name: '',
                    purpose_id: '',
                    assessment_type: '',
                    duration: '',
                    test_settings: [],
                    section_settings: [],
                    order_settings: [],
                    selected_question: [],
                    start_message: '',
                    end_message: '',
                },
                select_category_question:'',
                select_test_question:'',
                purposes: [],
                criterias: [],
                categories: [],
                test_list: [],
                select_selection: 1,
                selected_skills: '',
                question_list: [],
                model_show: false,
                wait_enable: false,
                table_questions: [],
                total_marks: 0,
                total_questions: 0,
                total_sections: 0,
                tooltip: {
                    show: 'Show',
                    edit: 'Edit',
                    delete: 'Delete'
                },
                min_datetime: '',
                public_id: '',
            }
        },
        mounted() {
            this.getCurrentDate()
            this.getPurposes()
            this.getCriteria()
            this.getCategory()
            this.getTest()

        },
        methods:{
            async getTest() {
                Test.show(this.$route.params.id).then(response => {

                    const { name, purpose_id,  assessment_type, duration, public_id, start_message, end_message } = response.data.test

                    this.public_id = public_id
                    this.test.name = name
                    this.test.purpose_id = purpose_id
                    this.test.start_message = start_message
                    this.test.end_message = end_message
                    if(assessment_type == 'Timed Assessment') {
                        this.test.assessment_type = 1
                    }
                    else {
                        this.test.assessment_type = 2
                    }

                    this.test.duration = duration

                    const { test_settings, section_settings, order_settings, selected_questions } = response.data

                    this.test.test_settings = test_settings
                    this.test.section_settings = section_settings
                    this.test.order_settings = order_settings

                    this.test.selected_question = selected_questions
                    this.DoneModel()

                    this.loader_spin = false

                }).catch(error=> {
                    this.loader_spin = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                })
            },
            async getPurposes() {
              Testpurpose.index().then(response => {
                this.purposes = response.data.purpose;
              })
              .catch(error=> {
                this.purposes = []
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
              });
            },
            async getCriteria() {
                Test.criterias().then(response => {
                    this.criterias = response.data.criterias;
                }).catch(error=> {
                    this.criterias = []
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                });
            },
            async getCategory() {
                Category.index().then(response => {
                    this.categories = response.data.categories;
                }).catch(error=> {
                    this.categories = []
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                });
            },
            async update() {
                this.disabled = true
                console.log(this.test)
                Test.update(this.$route.params.id, this.test).then(response => {
                    this.$toast.success(response.data.message);
                    this.$router.push({name:"Test"})
                    this.disabled = false
                }).catch(error=>{
                    this.disabled = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                })
            },
            async SelectedSkills(event) {
                this.question_list = []
                if(event.target.value == 1) {
                    this.loader_spin = true
                    Test.getAll().then(response => {
                        this.test_list = response.data.tests
                        this.model_show = true
                        this.loader_spin = false
                    }).catch(error=> {
                        this.loader_spin = false
                        if (error.response.status === 401) {
                            this.$toast.error(error.response.data.message);
                            this.$router.push({ name: "Login" });
                        }
                    });
                }
                else if(event.target.value == 2 ) {
                    this.model_show = true
                }
                else {
                    let routeData = this.$router.resolve({name: 'QuestionAdd'});
                    window.open(routeData.href);
                    this.selected_skills = ''
                }
            },
            async closed() {
                this.model_show = false
                this.loader_spin = false
                this.selected_skills = ''
            },
            async GetSelectedTestQuestion(event) {
                this.wait_enable = true
                Question.customTest(event.target.value).then(response => {
                    this.question_list = response.data.questions
                    this.wait_enable = false
                }).catch(error=> {
                    this.wait_enable = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                });
            },
            async GetSelectedCategoryQuestion(event) {
                this.wait_enable = true
                Question.custom(event.target.value).then(response => {
                    this.question_list = response.data.questions
                    this.wait_enable = false
                }).catch(error=> {
                    this.wait_enable = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                });
            },
            async DoneModel() {
                this.model_show = false

                var ObjQuestion = []
                var ObjTotalMarks = 0
                var ObjSections = {}

                if(this.test.selected_question.length > 0) {

                    ObjQuestion = this.test.selected_question

                    this.test.selected_question.forEach(element => {
                        ObjTotalMarks = ObjTotalMarks + element.marks;

                        var category = element.category
                        if(!ObjSections[category]) {
                           ObjSections[category] = []
                        }

                        ObjSections[category].push(element.question_id)
                    })
                }

                this.table_questions = ObjQuestion
                this.total_marks = ObjTotalMarks
                this.total_questions = this.test.selected_question.length
                this.total_sections = Object.keys(ObjSections).length

                this.selected_skills = ''
            },
            async deleteQuestionFromTest(index) {
                this.$delete(this.test.selected_question, index)
            },
            async getCurrentDate() {
                this.min_datetime = moment(new Date()).format('YYYY-MM-DD hh:mm a')
            }
        }
    }
</script>
