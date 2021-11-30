<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Preview Test</h1>    
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link small">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">Preview Test</p>
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
                                <h3 class="card-title fw-bolder text-dark m-0">{{ test_name }}</h3>
                                <div class="d-flex align-items-center py-1">
                                    <div class="d-flex align-items-center">
                                        <p class="m-0 pe-2">Duration for Test:</p>
                                        <p class="m-0 text-primary fw-bolder min-w-75px">{{ timerCount }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="separator mb-2"></div>
                            <div class="card-body">
                                <div class="row mb-6">
                                                <div class="col-md-4 col-12">
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

                                                <div class="col-md-4 col-12"></div>

                                                <div class="col-md-4 col-12">
                                                    <div class="d-flex align-items-center">
                                                        <p class="mb-0 me-3">Test Status:</p>
                                                        <div class="progress flex-grow-1">
                                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 30%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- section and progress bar ends -->

                                            <!-- question and options -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="ques-title bg-secondary px-6 py-5 rounded">
                                                        <h4 class="m-0 text-dark d-flex align-items-center"><strong class="h-40px w-40px rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-4">1</strong>Which of the following is true about the anonymous inner class?</h4>
                                                    </div>

                                                    <div class="options mt-4">
                                                        <ul class="list-unstyled mb-0 mt-md-8 mt-4 ps-lg-7">
                                                            <li class="mb-5">
                                                                <label class="radio">
                                                                    <input type="radio" name="radios2">
                                                                    <span class="me-2"></span>
                                                                    It has only methods
                                                                </label>
                                                            </li>

                                                            <li class="mb-5">
                                                                <label class="radio">
                                                                    <input type="radio" name="radios2">
                                                                    <span class="me-2"></span>
                                                                    Objects can't be created
                                                                </label>
                                                            </li>

                                                            <li class="mb-5">
                                                                <label class="radio">
                                                                    <input type="radio" name="radios2">
                                                                    <span class="me-2"></span>
                                                                    It has a fixed class name
                                                                </label>
                                                            </li>

                                                            <li>
                                                                <label class="radio">
                                                                    <input type="radio" name="radios2">
                                                                    <span class="me-2"></span>
                                                                    It has no class name
                                                                </label>
                                                            </li>

                                                        </ul>
                                                    </div>

                                                    <div class="w-100 ps-lg-7 mt-md-8 mt-5 ">
                                                        <a href="#" class="d-inline-block btn btn-dark">
                                                            Submit
                                                        </a> 
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- question and options ends -->

                                            <!--begin::Menu separator-->
                                            <div class="separator mb-2 mt-7"></div>
                                            <div class="row mb-6" style="display:none">
                                    <div class="col-md-4 col-12">
                                        <div class="form-group row align-items-center" v-if="sections.length > 0">
                                            <label class="py-0 fw-bold col-md-4 col-form-label col-12">Section <span><strong>1</strong></span> of {{ sections.length }}</label>
                                            <div class="col-md-4 col-12">
                                                <select class="form-control form-control-solid form-control-sm">
                                                    <option v-for="section in sections" :value="section.id">{{ section.name }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12"></div>
                                    <div class="col-md-4 col-12">
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0 me-3">Test Status:</p>
                                            <div class="progress flex-grow-1">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" :style="`width: ${progress}%;`" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" v-if="questions.length > 0" v-for="(question,key) in questions" :key="key">
                                    <div class="col-md-12">
                                        <div class="ques-title bg-secondary px-6 py-5 rounded">
                                            <h4 class="m-0 text-dark d-flex align-items-center"><strong class="h-40px w-40px rounded-circle bg-dark text-white d-flex align-items-center justify-content-center me-4">{{ key + 1 }}</strong> {{ question.question.title }}</h4>
                                        </div>
                                        <div class="options mt-4" v-if="question.question.options.length > 0">
                                            <ul class="list-unstyled mb-0 mt-md-8 mt-4 ps-lg-7">
                                                <li class="mb-5" v-for="(option,index) in question.question.options">
                                                    <label class="radio">
                                                        <input type="radio" name="radios2" >
                                                        <span class="me-2"></span>
                                                        {{ option.option }}
                                                    </label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="separator mb-2 mt-7"></div>
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
    </div>
</template>

<script>
    
    import Online from "../../apis/Online";

    export default {
        name:"edit-test",
        data(){
            return {
                disabled: false,
                loader_spin: true,
                test_name: '',
                duration: '',
                timerCount: '',
                sections: [],
                questions: [],
                progress: '0%'
            }
        },
        mounted() {
            this.getTest()
            this.getQuestion()
        },
        methods:{
            async getTest() {
                Online.index(this.$route.params.id).then(response => {

                    const { name, purpose, duration } = response.data.test

                    this.test_name = name + " - " + purpose.purpose
                    this.duration = duration*60

                    this.startTest()

                    if(response.data.categories) {
                        this.sections = response.data.categories
                    }

                    this.questions = response.data.test_questions

                    console.log(response)

                    this.loader_spin = false

                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async startTest() {
                if(this.duration > 0) {
                    setTimeout(() => {
                        this.duration -= 1
                        var hours = Math.floor(this.duration / (60 * 60));
                        var divisor_for_minutes = this.duration % (60 * 60);
                        var minutes = Math.floor(divisor_for_minutes / 60);
                        var divisor_for_seconds = divisor_for_minutes % 60;
                        var seconds = Math.ceil(divisor_for_seconds);

                        if(hours == 0) {
                            hours = '00'
                        }

                        if(minutes == 0) {
                            minutes = '00'
                        }

                        if(seconds == 0) {
                            seconds = '00'
                        }

                        this.timerCount = hours+'h '+minutes+'m '+seconds+'s'

                        this.startTest()
                    }, 1000)
                }
                else {
                    
                }
            }
            
        }
    }
</script>