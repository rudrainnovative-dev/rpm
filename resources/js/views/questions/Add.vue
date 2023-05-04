<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Add Question</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-6">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Question"}' class="text-link fs-6">Question Bank</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Add Question</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-12 col-12">
                        <div class="d-flex align-items-center justify-content-md-end mb-4">
                            <router-link :to='{name:"Question"}' class="btn btn-sm btn-secondary">Back to List</router-link>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch w-100">
                            <div class="card-body">
                                <form @submit.prevent="create">
                                    <div class="row mb-3">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="ques-category" class="mb-3 fw-bolder fs-5">Question Category<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-solid form-control-sm status-filter" v-model="question.category_id" placeholder="Question Category" required>
                                                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="ques-name" class="mb-3 fw-bolder fs-5">Question<span class="text-danger">*</span></label>
                                                <tinymce id="tinymce1" v-model="question.title" placeholder="Enter Question" required></tinymce>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-13 mb-3">
                                        <div class="col-md-12 col-12 mb-3">
                                            <h4 class="fw-bolder">Answer</h4>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[1]"  placeholder="Choice One" required>
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="1" required>
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control form-control-sm" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 1" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[2]"  placeholder="Choice Two" required>
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="2" required>
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control form-control-sm" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 2" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[3]"  placeholder="Choice Three" required>
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="3" required>
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control form-control-sm" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 3" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[4]" placeholder="Choice Four" required>
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="4" required>
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control form-control-sm" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 4" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 my-8">
                                            <textarea class="form-control form-control-solid form-control-sm" v-model="question.answers_justification" placeholder="Answer Justification"></textarea>
                                        </div>

                                        <div class="col-md-12 col-12 text-md-end">
                                            <ul class="list-unstyled list-inline m-0">
                                                <li class="list-inline-item">
                                                    <button type="submit" class="btn btn-dark btn-sm" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Submit</button>
                                                </li>
                                                <li class="list-inline-item">
                                                    <router-link :to='{name:"Question"}' class="btn btn-light btn-sm">Cancel</router-link>
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
    </div>
</template>

<script>
import Category from "../../apis/Category";
import Question from "../../apis/Question";

export default {
    name:"add-question",
    data(){
        return {
            question:{
                title:"",
                category_id:"",
                options: [],
                correct: '',
                marks: '',
                answers_justification:"",
            },
            categories:[],
            disabled: false
        }
    },
    mounted() {
        this.getCategories()
    },
    methods:{
        async create(){

            if(!this.question.correct) {
                this.$toast.error('Checked atleast one correct option.');
                return false
            }

            if(!this.question.title) {
                this.$toast.error('Question field required.');
                return false
            }

            this.disabled = true
            Question.add(this.question).then(response => {
                this.$toast.success(response.data.message);
                this.$router.push({name:"Question"})
                this.disabled = false
            }).catch(error=> {
                if (error.response.status === 400) {
                    this.$toast.error(error.response.data.error);
                }
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
                this.disabled = false
            })
        },
        async getCategories() {
          Category.index().then(response => {
            this.categories = response.data.categories;
          })
          .catch(error=> {
            this.categories = []
            if (error.response.status === 401) {
                this.$toast.error(error.response.data.message);
                this.$router.push({ name: "Login" });
            }
          });
        }
    }
}
</script>