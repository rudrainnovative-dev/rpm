<template>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">
            <div class="row align-items-center position-relative py-2">
                <div class="col-md-3 col-12">
                    <div class="logo w-100">
                        <a href="javascript:void(0);" class="mt-5 pb-lg-0 pb-10 display-4 text-dark">RPM</a>
                    </div>
                </div>
                <div class="col-md-5 col-12 test-title position-md-absolute start-0 end-0 top-md-50 text-md-center mx-auto translate-md-middle-y">
                    <h3 class="card-title fw-bolder text-dark m-0" v-if="test_screen">{{ test.name }}</h3>
                </div>
                <div class="col-md-4 col-12 position-md-absolute end-0 top-md-50 text-center mx-auto translate-md-middle-y">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <p class="m-0 pe-2" v-if="test_screen">Test Duration: </p>
                        <p id="timer-custom" class="m-0 text-primary fw-bolder min-w-80px" v-if="test_screen">{{ test.time_counter }}</p>
                    </div>
                </div>
            </div>
            <div class="row" v-if="validateTest">
                <Remember v-if="proceed" />
                <div class="col-md-12 col-12 py-4 text-right" v-if="proceed">
                    <button type="button" class="btn btn-sm btn-primary" v-on:click="proceedClick">Proceed</button>
                </div>
                <div class="col-md-12 col-12 py-4" v-if="registation">
                    <Registation :registation_fields="registation_fields" :test_id="test.id" v-on:childToParent="onRegistationClick"/>
                </div>
                <div class="col-md-12 col-12 py-4" v-if="test_screen">
                    <div class="w-100">
                        <div class="separator mb-5"></div>
                        <div class="row align-items-center">
                            <div class="col-md-5 col-12">
                                <div class="form-group row align-items-center">
                                    <label for="section" class="py-0 fw-bold col-md-4 col-form-label col-12">Section <span><strong>{{ selected_category.key }}</strong></span> of {{ categories.length }}</label>
                                    <div class="col-md-4 col-12">
                                        <select class="form-control form-control-solid form-control-sm" v-model="selected_category" v-if="categories.length > 0" v-on:change="categoryChanged">
                                            <option v-bind:value="{ key: key+1, id: category.id, info: category.info, name: category.name }" v-for="(category,key) in categories">{{ category.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12"></div>
                            <div class="col-md-3 col-12">

                            </div>
                        </div>
                        <div class="separator mb-5"></div>
                        <div class="row py-2" v-if="selected_category.info">
                            <div class="col-md-12 col-12">
                                <div class="alert alert-info">
                                    <h5>Section Instruction for <u>{{ selected_category.name }}</u></h5>
                                    <p class="mt-4">{{ selected_category.info }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="separator mb-5"></div>
                        <div class="w-100 my-6" v-if="test.question.data && Object.keys(test.question.data).length > 0">
                            <div class="row" v-for="(q,k) in test.question.data" :key="k">
                                <div class="col-md-12">
                                    <div class="ques-title bg-secondary px-6 py-5 rounded">
                                        <h4 class="m-0 text-dark d-flex align-items-center"><strong class="h-40px w-40px rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-4">{{ test.question.current_page }}</strong> {{ q.question.title }}</h4>
                                    </div>
                                    <div class="options mt-4">
                                        <ul class="list-unstyled mb-0 mt-md-8 mt-4 ps-lg-7" v-if="q.question.options.length > 0">
                                            <li class="mb-5" v-for="(option,index) in q.question.options">
                                                <label class="radio">
                                                    <input type="radio" :name="'option'+q.question_id" v-model="selected_answers[q.question_id]" :value="index" v-on:change="answerSelected(q.question_id, q.question.title, q.question.options, q.question.correct, q.question.marks, index, test.id, q.category.id, q.category.name)">
                                                    <span class="me-2"></span>
                                                    {{ option.option }}
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="separator mb-2 mt-7"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="w-100 ps-lg-7 mt-md-8 mt-5 ">
                                        <button type="button" class="d-inline-block btn btn-secondary btn-sm mr-2" v-on:click="getQuestion(test.question.current_page -1 )" v-if="test.question.current_page <= test.question.total && test.question.current_page != 1">Prev Question</button> 
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="getQuestion(test.question.current_page + 1)" v-if="test.question.current_page < test.question.total">Next Question</button>
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="nextSection()" v-if="test.question.current_page == test.question.total && categories.length > selected_category.key">Next Section</button>
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="finishTest()" v-if="test.question.current_page == test.question.total && categories.length == selected_category.key">Finish Test</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-center flex-wrap">
                                        <pagination :data="test.question" :limit="2" @pagination-change-page="getQuestion"></pagination>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-100 my-6" v-if="test.questions.length > 0">
                            <div class="row" v-for="(q,k) in test.questions" :key="k">
                                <div class="col-md-12">
                                    <div class="ques-title bg-secondary px-6 py-5 rounded">
                                        <h4 class="m-0 text-dark d-flex align-items-center"><strong class="h-40px w-40px rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-4">{{ k+1 }}</strong> {{ q.question.title }}</h4>
                                    </div>
                                    <div class="options mt-4">
                                        <ul class="list-unstyled mb-0 mt-md-8 mt-4 ps-lg-7" v-if="q.question.options.length > 0">
                                            <li class="mb-5" v-for="(option,index) in q.question.options">
                                                <label class="radio">
                                                    <input type="radio" :name="'option'+q.question_id" v-model="selected_answers[q.question_id]" :value="index" v-on:change="answerSelected(q.question_id, q.question.title, q.question.options, q.question.correct, q.question.marks, index, test.id, q.category.id, q.category.name)">
                                                    <span class="me-2"></span>
                                                    {{ option.option }}
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="separator mb-2 mt-7"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="w-100 ps-lg-7 mt-md-8 mt-5 ">
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="nextSection()" v-if="categories.length > selected_category.key">Next Section</button>
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="finishTest()" v-if="categories.length == selected_category.key">Finish Test</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="thankyou_screen">
                    <div class="col-md-12 my-8">
                        <div class="alert alert-info">
                            <h4>Thank you</h4>
                            <span class="d-block">Your test have been successfully submited.</span>
                            <span class="d-block">You can closed this window.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-8" v-else>
                <div class="col-md-12 col-12 text-center">
                    <div class="border border-secondary p-8">
                        <span class="text-dark font-weight-blod">{{ test.errors.validate }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading_front" v-if="loader_spin">
            <div class="loader"></div>
        </div>
    </div>
</template>

<script>
    
    import Online from "../../apis/Online"
    import Remember from './Remember'
    import Registation from './Registation'

    export default {
        name:"online-test",
        components: { Remember, Registation },
        mounted() {
            this.getTest()
        },
        data() {
            return {
               proceed: false,
               validateTest: false,
               registation: false,
               test_screen: false,
               thankyou_screen: false,
               loader_spin: false,
               registation_fields: '',
               selected_answers: [],
               test: {
                    id: '',
                    name:'',
                    time_counter:'',
                    taker_id: '',
                    taker_email: '',
                    categories: '',
                    selected_category: '',
                    settings: [],
                    question: {},
                    questions: [],
                    errors: {
                        validate: ''
                    }
               },
               temp_media: ''
            }
        },
        methods:{
            async getTest() {
                this.loader_spin = true
                Online.index(this.$route.params.id).then(response => {
                    this.validateTest = true
                    this.proceed = true

                    const { id, name, registation_fields, duration } = response.data.test
                    this.registation_fields = registation_fields
                    this.test.id = id
                    this.test.name = name
                    this.categories = response.data.category
                    this.selected_category = { key: 1, id: this.categories[0].id, info: this.categories[0].info, name: this.categories[0].name }
                    this.test.settings = response.data.test_settings

                    if(this.test.settings.includes(12)) {
                        this.getQuestion()
                    }
                    else {
                        this.getQuestions()
                    }

                    this.loader_spin = false
                }).catch(error=> {
                    if(error.response.status === 400) {
                        this.test.errors.validate = error.response.data.error
                    }
                    this.loader_spin = false
                })
            },
            async proceedClick() {
                this.proceed = false
                this.registation = true
            },
            async onRegistationClick(response) {
                this.test.taker_id = response.id
                this.test.taker_email = response.email
                if(this.test.settings.includes(4)) {
                    this.registation = false,
                    this.test_screen = true
                }
                else {
                    this.registation = false,
                    this.test_screen = true
                }
            },
            async categoryChanged() {
                if(this.test.settings.includes(12)) {
                    this.getQuestion()
                }
                else {
                    this.getQuestions()
                }
            },
            async getQuestion(page = 1) {
                this.loader_spin = true
                Online.question(this.test.id, this.selected_category.id, page).then(response => {
                    this.test.question = response.data.question
                    //this.test.total_question = this.question.total
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                })
                console.log(this.selected_answers)
            },
            async getQuestions() {
                this.loader_spin = true
                Online.questions(this.test.id, this.selected_category.id).then(response => {
                    this.test.questions = response.data.questions
                    //this.test.total_question = this.question.total
                    this.loader_spin = false
                    console.log(this.selected_answers)
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async answerSelected(question_id, question_title, question_options, correct, marks, selected, test_id, category_id, category_name) {
                var answered = [];
                answered = {'question_id': question_id, 'question': question_title, 'taker_id': this.test.taker_id, 'options': question_options, 'correct': correct, 'marks': marks, 'selected': selected, 'test_id': test_id, 'category_id': category_id, 'category': category_name}
                Online.answerSave(answered).then(response => {
                    if(response.data.action == 'create') {
                        this.solved = this.solved + 1
                    }
                    this.selected_answers[question_id] = selected 
                }).catch(error=> {

                })
            },
            async nextSection() {
                var key = this.selected_category.key + 1
                var index = key - 1
                this.selected_category = { key: key, id: this.categories[index].id, info: this.categories[index].info, name: this.categories[index].name }
                if(this.test.settings.includes(12)) {
                    this.getQuestion()
                }
                else {
                    this.getQuestions()
                }
            },
            async finishTest() {
                
                if(confirm("Are you sure you want to finish this test?")){
                    this.registation = false
                    this.test_screen = false
                    this.thankyou_screen = true
                }


            }

        }
    }
</script>