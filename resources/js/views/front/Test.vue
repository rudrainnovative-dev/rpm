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
            <div class="row" v-if="!expire">
                <div class="col-md-12 col-12 d-flex">
                    <div class="w-100">
                        <div class="separator mb-5"></div>
                        <div class="row align-items-center">
                            <div class="col-md-5 col-12">
                                <div class="form-group row align-items-center">
                                    <label for="section" class="py-0 fw-bold col-md-4 col-form-label col-12">Section <span><strong>{{ selected_category.key }}</strong></span> of {{ categories.length }}</label>
                                    <div class="col-md-4 col-12">
                                        <select class="form-control form-control-solid form-control-sm" v-model="selected_category" v-if="categories.length > 0" v-on:change="sectionChanged">
                                            <option v-bind:value="{ key: key+1, id: category.id, info: category.info, name: category.name }" v-for="(category,key) in categories">{{ category.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-12"></div>
                            <div class="col-md-3 col-12">
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 me-3">Test Status:</p>
                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" :style="{ width: (solved * 100)/totals + '%' }" :aria-valuenow="(solved * 100)/totals" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator mb-5"></div>
                        <div class="row py-2" v-if="selected_category.info">
                            <div class="col-md-12 col-12">
                                <div role="alert" class="alert alert-dismissible fade show position-relative p-0 border-secondary p-6">
                                    <div class="row">
                                        <a href="javascript:void(0)" data-bs-dismiss="alert" aria-label="Close" class="btn btn-icon btn-sm btn-light-dark bg-image-none btn-close bottom-0 end-0 top-unset opacity-100">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                        <h5 class="d-flex" style="line-height: 28px"><i class="fa fa-2x fa-info-circle bg-dark text-white rounded-circle mr-3"></i> Section Instruction for {{ selected_category.name }}</h5>
                                        <p class="mx-12 my-2">{{ selected_category.info }}</p>
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
                                                    <input type="radio" :name="`questionoption_${quest.question_id}`" v:model="selectedOptions[quest.question_id]" :value="option.id" v-on:change="answerSelected(quest.question_id, quest.question.title, quest.question.options, quest.question.correct, quest.question.marks, index, test_id, quest.category.id, quest.category.name)">
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

                        <div class="w-100 my-6" v-if="questions.length > 0">
                            <div class="row" v-for="(quest,key) in questions" :key="key">
                                <div class="col-md-12">
                                    <div class="ques-title bg-secondary px-6 py-5 rounded">
                                        <h4 class="m-0 text-dark d-flex align-items-center"><strong class="h-40px w-40px rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-4">{{ key+1 }}</strong> {{ quest.question.title }}</h4>
                                    </div>
                                    <div class="options mt-4">
                                        <ul class="list-unstyled mb-0 mt-md-8 mt-4 ps-lg-7" v-if="quest.question.options.length > 0">
                                            <li class="mb-5" v-for="(option,index) in quest.question.options">
                                                <label class="radio">
                                                    <input type="radio" :name="`questionoption_${quest.question_id}`" v:model="selectedOptions[quest.question_id]" :value="option.id" v-on:change="answerSelected(quest.question_id, quest.question.title, quest.question.options, quest.question.correct, quest.question.marks, index, test_id, quest.category.id, quest.category.name)">
                                                    <span class="me-2"></span>
                                                    {{ option.option }}
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="separator mb-2 mt-7"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-md-12 col-12 my-8">
                    <div class="alert alert-dismissible fade show position-relative p-0 border-secondary p-6">
                        <div class="row text-center">
                            <h5>Thank You</h5>
                            <p>{{ test_finish_message }}</p>
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
                expire: false,
                test_finish_message: 'Expire',
                registation_fields: [],
                categories: [],
                selected_category: '',
                test_id: '',
                test_name: '',
                test_time_counter: '',
                duration: '',
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
                /*else {
                    let test_taker_access = localStorage.test_taker_access_token
                    if(test_taker_access) {
                        this.preview = true
                    }
                }*/

                Online.index(this.$route.params.id).then(response => {

                    const { id, name, registation_fields, duration } = response.data.test
                    
                    this.registation_fields = registation_fields
                    this.test_id = id
                    this.test_name = name
                    this.duration = duration * 60
                    this.test_settings = response.data.test_settings

                    this.categories = response.data.category
                    this.selected_category = { key: 1, id: this.categories[0].id, info: this.categories[0].info, name: this.categories[0].name }
                    
                    if(this.test_settings.includes(12)) {
                        this.getQuestion()
                    }
                    else {
                        this.getQuestions()
                    }
                    
                    this.timeCounter()

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
                        this.timeCounter()
                    }
                    this.disabled=false
                    this.loader_spin = true
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
                this.loader_spin = true
                Online.question(this.test_id, this.selected_category.id, page).then(response => {
                    this.question = response.data.question
                    this.questionData = response.data.question.data
                    this.totals = this.question.total
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async getQuestions() {
                this.loader_spin = true
                Online.questions(this.test_id, this.selected_category.id).then(response => {

                    console.log(response)
                    this.questions = response.data.questions
                    this.totals = this.questions.length
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async answerSelected(question_id, question_title, question_options, correct, marks, selected, test_id, category_id, category_name) {
                
                var answered = [];
                var taker_id = localStorage.test_taker_id
                answered = {'question_id': question_id, 'question': question_title, 'category': 'dd', 'taker_id': taker_id, 'options': question_options, 'correct': correct, 'marks': marks, 'selected': selected, 'test_id': test_id, 'category_id': category_id, 'category_name': category_name}

                Online.answerSave(answered).then(response => {
                    if(response.data.action == 'create') {
                        this.solved = this.solved + 1
                    }
                }).catch(error=> {

                })

            },
            async timeCounter() {
                if(this.duration > 0) {
                    setTimeout(() => {
                        this.duration -= 1
                        
                        var hours = Math.floor(this.duration / (60 * 60));
                        var divisor_for_minutes = this.duration % (60 * 60);
                        var minutes = Math.floor(divisor_for_minutes / 60);

                        var divisor_for_seconds = divisor_for_minutes % 60;
                        var seconds = Math.ceil(divisor_for_seconds);

                        this.test_time_counter = hours+'h '+minutes+'m '+seconds+'s'

                        if(this.duration == 1) {
                            this.expire = true
                        }

                        this.timeCounter()
                    }, 1000)
                }
            },
            async sectionChanged() {
                if(this.test_settings.includes(12)) {
                    this.getQuestion()
                }
                else {
                    this.getQuestions()
                }
            }
            

        }
    }
</script>