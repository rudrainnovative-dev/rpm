<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Test Questions</h1>    
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-6">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link fs-6">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Test Questions</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-12 col-12 text-right mb-2">
                        <router-link :to='{name:"Test"}' class="btn btn-sm btn-secondary ms-0 mb-2">Back</router-link>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch mb-xl-8 w-100">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-12 mt-4">
                                        <h4 class="fw-bolder">{{ test_name }}</h4>
                                    </div>
                                    <div class="col-md-12 col-12 mt-4">
                                        <table class="table table-rounded table-striped border m-0">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                    <th class="fw-bolder align-middle">Section Name</th>
                                                    <th class="fw-bolder align-middle text-center">No. of Question</th>
                                                    <th class="fw-bolder align-middle text-center">Correct Grade</th>
                                                    <th class="fw-bolder align-middle text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="test_questions_data.length > 0">
                                               <tr v-for="(item, key) in test_questions_data" :key="key">
                                                    <td class="align-middle">{{ item.category.name }}</td>
                                                    <td class="align-middle text-center">{{ key+1 }} out of {{ test_questions_data.length }}</td>
                                                    <td class="align-middle text-center">{{ item.question.marks }}</td>
                                                    <td class="align-middle text-center">
                                                        <router-link :to='{name:"QuestionShow", params: {id: item.question_id}}' class="text-link small"><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" v-tooltip="tooltip.show"><i class="p-0 fa fa-eye"></i></button></router-link>
                                                    </td>
                                               </tr>
                                            </tbody>
                                            <tbody v-else>
                                               <tr>
                                                    <td class="align-middle" colspan="4">No any question selected.</td>
                                               </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-12 mt-5">
                                        <div class="d-flex justify-content-end align-items-center flex-wrap">
                                            <pagination :data="test_questions" :limit="2" @pagination-change-page="getTestQuestions"></pagination>
                                        </div>
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

    export default {
        name:"test-question",
        data(){
            return {
                disabled: false,
                loader_spin: false,
                test_questions: {},
                test_questions_data: [],
                test_name: '',
                tooltip: {
                    show: 'View Question'
                }
            }
        },
        mounted() {
            this.getTestQuestions()
        },
        methods:{
            async getTestQuestions(page=1) {
                this.loader_spin = true
                Test.testQuestions(this.$route.params.id, page).then(response => {

                    this.test_name = response.data.test.name
                    this.test_questions_data = response.data.test_questions.data
                    this.test_questions = response.data.test_questions
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                })
            }
        }
    }
</script>