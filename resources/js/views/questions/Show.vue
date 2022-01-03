<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Question #{{ this.$route.params.id }}</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-6">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Question"}' class="text-link fs-6">Question Bank</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Question #{{ this.$route.params.id }}</p>
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
                                <div class="row mb-3">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group">
                                            <label for="ques-category" class="mb-2 fw-bolder fs-5">Question Category<span class="text-danger">*</span></label>
                                            <select class="form-control form-control-solid form-control-sm" v-model="question.category_id" placeholder="Question Category" disabled required>
                                                <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mb-3">
                                    <div class="col-md-12 col-12">
                                        <div class="form-group">
                                            <label for="ques-name" class="mb-2 fw-bolder fs-5">Question<span class="text-danger">*</span></label>
                                            <div v-html="question.title"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-13  mb-3">
                                    <div class="col-md-12 col-12 mb-3">
                                        <h4 class="fw-bolder">Answer</h4>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[1]"  placeholder="Choice One" disabled>
                                        <div class="form-group checkbox-list mt-3">
                                            <div class="radio-inline">
                                                <label class="radio mb-3">
                                                    <input type="radio" v-model="question.correct" :value="1" disabled />
                                                    <span></span> Correct Answer
                                                </label>
                                            </div>
                                            <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 1" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[2]"  placeholder="Choice Two" disabled />
                                        <div class="form-group checkbox-list mt-3">
                                            <div class="radio-inline">
                                                <label class="radio mb-3">
                                                    <input type="radio" v-model="question.correct" :value="2" disabled />
                                                    <span></span> Correct Answer
                                                </label>
                                            </div>
                                            <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 2" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[3]"  placeholder="Choice Three" disabled />
                                        <div class="form-group checkbox-list mt-3">
                                            <div class="radio-inline">
                                                <label class="radio mb-3">
                                                    <input type="radio" v-model="question.correct" :value="3" disabled />
                                                    <span></span> Correct Answer
                                                </label>
                                            </div>
                                            <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 3" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-12">
                                        <input type="text" class="form-control form-control-solid form-control-sm" v-model="question.options[4]" placeholder="Choice Four" disabled />
                                        <div class="form-group checkbox-list mt-3">
                                            <div class="radio-inline">
                                                <label class="radio mb-3">
                                                    <input type="radio" v-model="question.correct" :value="4" disabled />
                                                    <span></span> Correct Answer
                                                </label>
                                            </div>
                                            <input type="number" class="form-control" v-model="question.marks" min= "1" placeholder="Marks for the Question" v-if="question.correct == 4" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12 my-8">
                                        <textarea class="form-control form-control-solid form-control-sm" v-model="question.answers_justification" placeholder="Answer Justification" disabled></textarea>
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
import Category from "../../apis/Category";
import Question from "../../apis/Question";

export default {
    name:"update-question",
    data(){
        return {
            question:{
                title:"",
                category_id:"",
                question_id: '',
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
                
                const { id, category_id, title, correct, marks, answers_justification } = response.data.question
                const options = response.data.options    

                this.question_id = id
                this.question.category_id = category_id
                this.question.title = title
                this.question.answers_justification = answers_justification
                this.question.correct = correct
                this.question.marks = marks

                var k = 1;
                for(var i=0; i< options.length; i++) {
                    this.question.options[k] = options[i].option
                    k++
                }

                this.loader_spin = false
            }).catch(error=> {
                this.loader_spin = false
            })

        },
        async getCategories() {
          Category.index().then(response => {
            this.categories = response.data.categories;
          })
          .catch(error=> {
            this.categories = []
          });
        }
    }
}
</script>
