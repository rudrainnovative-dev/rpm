<template>
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login" v-if="!preview">
        <div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background-image: url(/img/login-bg.jpg);">
            <div class="d-flex flex-row-fluid flex-column justify-content-between">
                <span class="flex-column-auto mt-5 pb-lg-0 pb-10 display-3 text-white">RPM</span>
                <div class="flex-column-fluid d-flex flex-column justify-content-center">
                    <h3 class="mb-5 text-white">Welcome to RPM</h3>
                    <p class="font-weight-lighter text-white opacity-80"></p>
                </div>
                <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
                    <div class="opacity-70 font-weight-bold text-white">© 2021 RPM</div>
                        <ul class="m-0 list-unstyled list-inline">
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="text-white">Privacy</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="text-white ml-10">Legal</a>
                            </li>
                            <li class="list-inline-item">
                                <a href="javascript:void(0);" class="text-white ml-10">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
                <div class="d-flex flex-column-fluid flex-center">
                    <div class="login-form login-signin mw-400px w-100 mx-auto">
                        <form class="form fv-plugins-bootstrap fv-plugins-framework" @submit.prevent="registation">
                            <div class="text-center mb-5 mb-lg-10">
                                <h1 class="">Registation</h1>
                                <p class="text-muted font-weight-bold">Before start the Test please fill the below details.</p>
                            </div>
                            <div class="form-group fv-plugins-icon-container mb-7">
                                <input type="text" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" placeholder="Name" autocomplete="off" v-model = "test_taker.name" required/>
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group fv-plugins-icon-container mb-7">
                                <input type="email" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" placeholder="Email" autocomplete="off" v-model = "test_taker.email" required/>
                                <span class="text-danger"></span>
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary font-weight-bold" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="loading" v-if="loader_spin">
                <div class="loader"></div>
            </div>
        </div>
    </div>

    <div class="post d-flex flex-column-fluid" id="kt_post" v-else>
        <div id="kt_content_container" class="container-fluid">
            <div class="row align-items-center position-relative py-2">
                <div class="col-md-3 col-12">
                    <div class="logo w-100">
                        <a href="javascript:void(0);" class="mt-5 pb-lg-0 pb-10 display-4 text-dark">RPM</a>
                    </div>
                </div>
                <div class="col-md-5 col-12 test-title position-md-absolute start-0 end-0 top-md-50 text-md-center mx-auto translate-md-middle-y">
                    <h3 class="card-title fw-bolder text-dark m-0">{{ test_name }}</h3>
                </div>
                <div class="col-md-4 col-12 position-md-absolute end-0 top-md-50 text-center mx-auto translate-md-middle-y">
                    <div class="d-flex align-items-center justify-content-md-end">
                        <p class="m-0 pe-2">Test Duration:</p>
                        <p id="timer-custom" class="m-0 text-primary fw-bolder min-w-80px">{{ test_time_counter }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-12 d-flex">
                    <div class="w-100">
                        <div class="separator mb-5"></div>
                        <div class="row align-items-center">
                            <div class="col-md-3 col-12">
                                <div class="form-group row align-items-center">
                                    <label for="section" class="py-0 fw-bold col-md-4 col-form-label col-12">Section <span><strong>1</strong></span> of 4</label>
                                    <div class="col-md-4 col-12">
                                        <select class="form-control form-control-solid form-control-sm" id="section">
                                            <option>1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12"></div>
                            <div class="col-md-3 col-12">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 me-3">Test Status:</p>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" :style="{ width: (solved * 100)/totals + '%' }" :aria-valuenow="(solved * 100)/totals" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 my-6" v-if="questionData.length > 0">
                            <div class="row" v-for="(quest,key) in questionData" :key="key">
                                <div class="col-md-12">
                                    <div class="ques-title bg-secondary px-6 py-5 rounded">
                                        <h4 class="m-0 text-dark d-flex align-items-center"><strong class="h-40px w-40px rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-4">{{ question.current_page }}</strong> {{ quest.question.title }}</h4>
                                    </div>
                                    <div class="options mt-4">
                                        <ul class="list-unstyled mb-0 mt-md-8 mt-4 ps-lg-7" v-if="quest.question.options.length > 0">
                                            <li class="mb-5" v-for="(option,index) in quest.question.options">
                                                <label class="radio">
                                                    <input type="radio" :name="`questionoption_${key}`" v:model="selectedOptions[quest.question.id]" :value="selectedOptions[quest.question.id]" v-on:change="answerSelected(quest.question.id, quest.question.title, quest.question.options, quest.question.correct, quest.question.marks, index)">
                                                    <span class="me-2"></span>
                                                    {{ option.option }}
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="w-100 ps-lg-7 mt-md-8 mt-5 ">
                                        <button type="button" class="d-inline-block btn btn-dark mr-2" v-on:click="getQuestion(question.current_page -1 )" v-if="question.current_page <= question.total && question.current_page != 1">Prev</button> 
                                        <button type="button" class="d-inline-block btn btn-dark" v-on:click="getQuestion(question.current_page + 1)" v-if="question.current_page < question.total">Next</button>
                                    </div>
                                </div>
                            </div>
                            <div class="separator mb-2 mt-7"></div>
                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                <pagination :data="question" :limit="2" @pagination-change-page="getQuestion"></pagination>
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
    
    import Online from "../../apis/Online";

    export default {
        name:"online-test",
        data() {
            return {
                disabled: false,
                loader_spin: true,
                preview: false,
                registation_fields: [],
                test_id: '',
                test_name: '',
                test_time_counter: '',
                test_settings: [],
                taker_id: '',
                test_taker: {
                    name: '',
                    email: '',
                    solved_questions: []
                },
                question: {},
                questionData: [],
                questions: [],
                totals: 0,
                solved: 0,
                selectedOptions: [],
            }
        },
        mounted() {
            this.getTest()
        },
        methods:{
            async getTest() {

                let token = localStorage.access_token;
                
                if(token) {
                    this.preview = true
                }
                else {
                    let test_taker_access = localStorage.test_taker_access_token
                    if(test_taker_access) {
                        this.preview = true
                    }
                }

                Online.index(this.$route.params.id).then(response => {

                    const { id, name, registation_fields } = response.data.test
                    
                    this.registation_fields = registation_fields
                    this.test_id = id
                    this.test_name = name

                    this.test_settings = response.data.test_settings

                    if(this.test_settings.includes(12)) {
                        this.getQuestion()
                    }
                    else {
                        this.getAllQuestion()
                    }
                    

                    //console.log(response)

                    

                }).catch(error=> {
                    this.loader_spin = false
                })
            }, 
            async registation() {
                this.disabled=true
                Online.register(this.test_taker, this.test_id).then(response => {
                    if(response.data.success) {
                        localStorage.setItem("test_taker_access_token", response.data.taker.id)
                        localStorage.setItem("test_taker_id", response.data.taker.id)
                        this.preview = true
                    }
                    this.disabled=false
                }).catch(error=> {
                    this.disabled=false
                    if (error.response.status === 422) {
                       this.$toast.error(error.response.data.errors);
                    }

                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                    }
                })
            },
            async getQuestion(page = 1) {
                Online.question(this.test_id, page).then(response => {
                    this.question = response.data.question
                    this.questionData = response.data.question.data
                    this.totals = this.question.total
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async getQuestions() {
                Online.questions(this.test_id).then(response => {
                    this.questions = response.data.questions
                    this.loader_spin = false
                    this.totals = this.questions.length
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async answerSelected(question_id, question_title, question_options, correct, marks, selected) {
                
                var answered = [];
                var taker_id = localStorage.test_taker_id
                answered = {'question_id': question_id, 'question': question_title, 'category': 'dd', 'taker_id': taker_id, 'options': question_options, 'correct': correct, 'marks': marks, 'selected': selected}

                Online.answerSave(answered).then(response => {
                    this.solved = this.solved + 1
                }).catch(error=> {

                })

            }
            

        }
    }
</script>