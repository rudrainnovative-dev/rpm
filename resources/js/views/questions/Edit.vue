<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Edit Question</h1>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-6 ">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Question"}' class="text-link fs-6">Question Bank</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Edit Question</p>
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
                            <div class="card-body">
                                <form @submit.prevent="update">
                                    <div class="row mb-3">
                                        <div class="col-md-4 col-6">
                                            <div class="form-group">
                                                <label for="ques-category" class="mb-2 fw-bolder fs-5">Question Category<span class="text-danger">*</span></label>
                                                <select class="form-control form-control-solid form-control-sm status-filter" v-model="question.category_id" placeholder="Question Category" required>
                                                    <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-6">
                                            <div class="d-flex align-items-center justify-content-md-end mb-4">
                                                <router-link :to='{name:"Question"}' class="btn btn-sm btn-secondary">Back to List</router-link>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="ques-name" class="mb-2 fw-bolder fs-5">Question<span class="text-danger">*</span></label>
                                                 <tinymce id="tinymce1" v-model="question.title" placeholder="Enter Question" required></tinymce>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-6">
                                        <div class="col-md-12 col-12 mb-3">
                                            <h4 class="fw-bolder">Answer</h4>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="hidden" v-model="question.option_ids[1]">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[1]"  placeholder="Choice One" required>
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="1" required />
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 1" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="hidden" v-model="question.option_ids[2]">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[2]"  placeholder="Choice Two" required />
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="2" required />
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 2" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                        <input type="hidden" v-model="question.option_ids[3]">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[3]"  placeholder="Choice Three" required />
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="3" required />
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 3" required />
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <input type="hidden" v-model="question.option_ids[4]">
                                            <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[4]" placeholder="Choice Four" required />
                                            <div class="form-group checkbox-list mt-3">
                                                <div class="radio-inline">
                                                    <label class="radio mb-3">
                                                        <input type="radio" v-model="question.correct" :value="4" required />
                                                        <span></span> Correct Answer
                                                    </label>
                                                </div>
                                                <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 4" required />
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12 my-8">
                                            <textarea class="form-control form-control-solid form-control-sm" v-model="question.answers_justification" placeholder="Answer Justification"></textarea>
                                        </div>

                                        <div class="col-md-12 col-12 text-md-end">
                                            <ul class="list-unstyled list-inline m-0">
                                                <li class="list-inline-item">
                                                    <button type="submit" class="btn btn-dark btn-sm" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span>  Update</button>
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
        <div class="loading" v-if="loader_spin">
            <div class="loader"></div>
        </div>
    </div>
</template>

<script>
import Category from "../../apis/Category";
import Question from "../../apis/Question";

export default {
    name:"update-question",
    data(){
        return {
            question:{
                title:"",
                category_id:"",
                option_ids: [],
                options: [],
                correct: '',
                marks: '',
                answers_justification:"",
                _method:"patch"
            },
            categories:[],
            loader_spin: true,
            disabled: false
        }
    },
    mounted(){
        this.getCategories()
        this.showQuestion()
    },
    methods:{
        async showQuestion() {
            Question.show(this.$route.params.id).then(response => {

                const { category_id, title, correct, marks, answers_justification } = response.data.question
                const options = response.data.options

                this.question.category_id = category_id
                this.question.title = title
                this.question.answers_justification = answers_justification
                this.question.correct = correct
                this.question.marks = marks

                var k = 1;
                for(var i=0; i< options.length; i++) {
                    this.question.option_ids[k] = options[i].id
                    this.question.options[k] = options[i].option
                    k++
                }

                this.loader_spin = false
            }).catch(error=> {
                this.loader_spin = false
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
            })

        },
        async update(){

            if(!this.question.correct) {
                this.$toast.error('Checked atleast one correct option.');
                return false
            }

            if(!this.question.title) {
                this.$toast.error('Question field required.');
                return false
            }

            this.disabled = true
            Question.update(this.$route.params.id, this.question).then(response => {
                this.$toast.success(response.data.message);
                this.$router.push({name:"Question"});
                this.disabled = false
            }).catch(error=>{
                this.disabled = false
                if (error.response.status === 401) {
                this.$toast.error(error.response.data.message);
                this.$router.push({ name: "Login" });
                }
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
