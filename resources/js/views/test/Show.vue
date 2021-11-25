<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">View Test</h1>    
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link small">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">View Test</p>
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
                                <h3 class="card-title fw-bolder text-dark m-0"></h3>
                                <div class="col-md-6 col-12 text-md-end">
                                    
                                </div>
                            </div>
                            <div class="separator"></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="mb-2 fw-bold">Test Name<span class="text-danger">*</span></label>
                                            <input type="test" class="form-control form-control-solid form-control-sm" v-model="test.name" placeholder="Enter Test Name" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label class="mb-2 fw-bold">Purpose of the Test<span class="text-danger">*</span></label>
                                            <select class="form-control form-control-solid form-control-sm" v-model="test.purpose_id" placeholder="Purpose of test" disabled>
                                                <option v-for="purpose in purposes" :value="purpose.id">{{ purpose.purpose }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12"></div>
                                    <div class="col-md-12 col-12 my-6">
                                        <div class="radio-inline">
                                            <label class="radio">
                                                <input type="radio" v-model="test.assessment_type" :value="1" name="assessment_type" disabled>
                                                <span></span>Time Assessment
                                            </label>
                                            <label class="radio">
                                                <input type="radio" v-model="test.assessment_type" :value="2" name="assessment_type" disabled>
                                                <span></span> Deadline Based Assessment
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch mb-xl-8 w-100">
                            <div class="separator"></div>
                                <div class="card-body">
                                    <div class="row">
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
                                        <div class="col-md-12 col-12 mt-8" id="selected_question_div">
                                            <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <th class="fw-bolder align-middle">Section Name</th>
                                                        <th class="fw-bolder align-middle">No. of Question</th>
                                                        <th class="fw-bolder align-middle">Correct Grade</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="table_questions.length > 0">
                                                   <tr v-for="(item,key) in table_questions" :key="key">
                                                        <td class="align-middle">{{ item.category }}</td>
                                                        <td class="align-middle">{{ key+1 }} out of {{ table_questions.length }}</td>
                                                        <td class="align-middle">{{ item.marks }}</td>
                                                   </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mb-10 mt-5">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group d-flex align-items-center">
                                                <label class="me-2 w-100 fw-bold">Test Duration (min)<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-solid form-control-sm" v-model="test.duration" disabled/>
                                            </div>
                                        </div>

                                        <div class="row mt-5">
                                            <div class="col-md-3 col-12">
                                                <div class="form-group d-flex align-items-center" v-if="test.assessment_type == 2">
                                                    <label class="control-label p-0 me-2 mb-0 fw-bold">From</label>
                                                    <input type="datetime" class="form-control form-control-solid form-control-sm mw-lg-200px" v-model="test.assessment_time_from" disabled />
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-12">
                                                <div class="form-group d-flex align-items-center"  v-if="test.assessment_type == 2">
                                                    <label class="control-label p-0 me-2 mb-0 fw-bold">To</label>
                                                    <input type="datetime" class="form-control form-control-solid form-control-sm mw-lg-200px" v-model="test.assessment_time_to" disabled />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <nav>
                                                <ul class="nav nav-dark nav-bold nav-tabs nav-tabs-line" id="nav-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link show fw-bold h5 px-4 py-3 active" id="test-setting-tab" data-bs-toggle="tab" href="#test-setting" role="tab" aria-controls="test-setting" aria-selected="false">Test Settings</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link fw-bold fw-bold h5 px-4 py-3" id="section-setting-tab" data-bs-toggle="tab" href="#section-setting" role="tab" aria-controls="section-setting" aria-selected="false">Section Settings</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                            <div class="tab-content pt-7" id="nav-tabContent">
                                                <div class="tab-pane fade active show" id="test-setting" role="tabpanel" aria-labelledby="test-setting-tab">
                                                    <div class="form-group checkbox-list mt-3">
                                                        <label class="checkbox mb-3" v-for="(criteria,key) in criterias" :key="key">
                                                            <input class="me-2" type="checkbox" v-model="test.test_settings" :value="criteria.id" disabled>
                                                            <span></span> {{ criteria.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="section-setting" role="tabpanel" aria-labelledby="section-setting-tab">
                                                    <div class="row mt-3">
                                                        <div class="col-md-2 py-3">
                                                            <label class="mb-2 fw-bold">Select Section</label>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group">
                                                                <select class="form-control form-control-solid form-control-sm" v-model="select_selection" disabled>
                                                                    <option :value="category.id" v-for="category in categories">{{ category.name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5">
                                                        <div class="col-md-12" v-for="cat_id in categories" :class="(select_selection == cat_id.id)?'d-block':'d-none'">
                                                            <label class="mb-2 fw-bold">Section Instructions</label><br>
                                                            <textarea class="form-control form-control-solid form-control-sm" rows="3" v-model="test.section_settings[cat_id.id]" placeholder="Remarks" disabled></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5 align-items-end">
                                                        <div class="col-md-4">
                                                            <label class="mb-2 fw-bold">Section Name</label>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="mb-2 fw-bold">Order</label>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                    <div class="row mt-1 align-items-end" v-for="category in categories">
                                                        <div class="col-md-4" >
                                                            <span>{{ category.name }}</span>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" min="0" class="form-control form-control-solid form-control-sm w-50" v-model="test.order_settings[category.id]" disabled>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <router-link :to='{name:"Test"}' class="btn btn-danger">Back</router-link>
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
    </div>
</template>

<script>
    
    import Test from "../../apis/Test";
    import Testpurpose from "../../apis/Testpurpose";
    import Question from "../../apis/Question";
    import Category from "../../apis/Category";

    export default {
        name:"edit-test",
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
                    assessment_time_from: '',
                    assessment_time_to: '',
                    test_settings: [],
                    section_settings: [],
                    order_settings: [],
                    selected_question: [],
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
            }
        },
        mounted() {
            this.getPurposes()
            this.getCriteria()
            this.getCategory()
            this.getTest()
        },
        methods:{
            async getTest() {
                Test.show(this.$route.params.id).then(response => {

                    const { name, purpose_id,  assessment_type, duration, assessment_time_from, assessment_time_to } = response.data.test

                    this.test.name = name
                    this.test.purpose_id = purpose_id
                    
                    if(assessment_type == 'Timed Assessment') {
                        this.test.assessment_type = 1
                    } 
                    else {
                        this.test.assessment_type = 2 
                    }

                    this.test.duration = duration
                    this.test.assessment_time_from = assessment_time_from
                    this.test.assessment_time_to = assessment_time_to

                    const { test_settings, section_settings, order_settings, selected_questions } = response.data

                    this.test.test_settings = test_settings
                    this.test.section_settings = section_settings
                    this.test.order_settings = order_settings
                    
                    this.test.selected_question = selected_questions
                    this.DoneModel()

                    this.loader_spin = false

                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async getPurposes() {
              Testpurpose.index().then(response => {
                this.purposes = response.data.purpose;
              })
              .catch(error=> {
                this.purposes = []
              });
            },
            async getCriteria() {
                Test.criterias().then(response => {
                    this.criterias = response.data.criterias;
                }).catch(error=> {
                    this.criterias = []
                });
            },
            async getCategory() {
                Category.index().then(response => {
                    this.categories = response.data.categories;
                }).catch(error=> {
                    this.categories = []
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
            }
        }
    }
</script>