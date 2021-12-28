<template>
    <div class="post d-flex flex-column-fluid" id="kt_post" @click.right.prevent @copy.prevent @paste.prevent>
        <div id="kt_content_container" class="container-fluid" ref="screenPrint">
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
                        <p id="timer-custom" class="m-0 fw-bolder min-w-100px" :class="(this.test.duration > 300)?'text-primary':'text-danger'" v-if="test_screen">{{ time_counter }}</p>
                    </div>
                </div>
            </div>
            <div class="row" v-if="validateTest">
                <Remember :camera_enable="camera_enable" :camera_error="camera_error" v-on:childToParent="onProceedClick" v-if="proceed" />
                <div class="col-md-12 col-12 py-4" v-if="test_screen">
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
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" :style="`width: ${progress_bar}%;`" :aria-valuenow="progress_bar" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator mb-5"></div>
                        <div class="row py-2" v-if="selected_category.info">
                            <div class="col-md-12 col-12">
                                <div class="alert alert-info">
                                    <h5>Section Instruction for <u>{{ selected_category.name }}</u></h5>
                                    <p class="mt-4">{{ selected_category.info }}</p>
                                    <p class="mt-4 text-danger" v-if="required_all">Note: Mark all Questions Mandatory</p>
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
                                                    <input type="radio" :name="'option'+q.question_id" v-model="selected_answers['q'+q.question_id]" :value="index" v-on:change="answerSelected(q.question_id, q.question.title, q.question.options, q.question.correct, q.question.marks, index, test.id, q.category.id, q.category.name)">
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
                                <div class="col-md-12 text-right mb-8">
                                    <div class="w-100 ps-lg-7 mt-md-8 mt-5 ">
                                        <button type="button" class="d-inline-block btn btn-secondary btn-sm mr-2" v-on:click="getQuestion(test.question.current_page -1 )" v-if="test.question.current_page <= test.question.total && test.question.current_page != 1">Prev Question</button> 
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="getQuestion(test.question.current_page + 1)" v-if="test.question.current_page < test.question.total">Next Question</button>
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="nextSection()" v-if="test.question.current_page == test.question.total && categories.length > selected_category.key">Next Section</button>
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="finishTest()" v-if="test.question.current_page == test.question.total && categories.length == selected_category.key">Finish Test</button>
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <div class="mt-4 align-items-center flex-wrap">
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
                                                    <input type="radio" :name="'option'+q.question_id" v-model="selected_answers['q'+q.question_id]" :value="index" v-on:change="answerSelected(q.question_id, q.question.title, q.question.options, q.question.correct, q.question.marks, index, test.id, q.category.id, q.category.name)">
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
                                <div class="col-md-12 text-right mb-8">
                                    <div class="w-100 ps-lg-7 mt-md-8 mt-5 ">
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="nextSection()" v-if="categories.length > selected_category.key">Next Section</button>
                                        <button type="button" class="d-inline-block btn btn-sm btn-dark" v-on:click="finishTest()" v-if="categories.length == selected_category.key">Finish Test</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="thankuscreen">
                    <div class="col-md-12 my-8 text-center" v-if="!report_display">
                        <div class="alert alert-info">
                            <h4>Thank you for taking the time.</h4>
                            <span class="d-block">Your test have been successfully submited.</span>
                            <span class="d-block">You can closed this window.</span>
                        </div>
                    </div>
                    <Reportdisplay :taker="test.taker_id" v-else/>
                </div>
                <div class="row" v-if="timeoutscreen">
                    <div class="col-md-12 my-8 text-center" v-if="!report_display">
                        <div class="alert alert-danger">
                            <h4 class="mb-7">Your time have been finished</h4>
                            <p>Please click the Finish Test button and save your Test.</p>
                            <button type="button" class="btn btn-sm btn-danger" v-on:click="finishTestTimeout">Finish Test</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row my-8" v-else>
                <div class="col-md-12 col-12 text-center">
                    <div class="border border-secondary py-8 px-4 rounded">
                        <strong class="text-dark">{{ test.errors.validate }}</strong>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading_front" v-if="loader_spin">
            <div class="loader"></div>
        </div>
        <div style="position: absolute;top: 0;right: 1px;width: 300px;height: 300px;border: solid 1px #e5e5e5;    z-index: -999999999;opacity: 0;" v-if="camera_enable"><video ref="camera" :width="300" :height="300" autoplay></video><canvas v-show="false" ref="canvas" :width="300" :height="300"></canvas></div>
    </div>
</template>

<script>
    import Online from "../../apis/Online"
    import Remember from '../front/Remember'
    import Reportdisplay from '../front/Report'

    export default {
        name:"online-test",
        components: { Remember, Reportdisplay },
        mounted() {
            this.getTest()
        },
        data() {
            return {
                test: {
                    id: '',
                    name:'',
                    duration: '',
                    taker_id: '',
                    taker_email: '',
                    categories: '',
                    selected_category: '',
                    settings: [],
                    question: {},
                    questions: [],
                    screensnap: '',
                    snap: '',
                    errors: {
                        validate: ''
                    },
                    preview: true
               },
               time_counter:'',
               camera_enable: false,
               camera_error: '',
               proceed: false,
               validateTest: false,
               total_questions: '',
               test_screen: false,
               thankuscreen: false,
               timeoutscreen: false,
               report_display: false,
               loader_spin: false,
               selected_answers: [],
               progress_bar: '',
               candidate: {
                email: '',
                name: '',
                preview: ''
               },
               required_all: false,
            }
        },
        methods:{
            async getTest() {
                this.loader_spin = true
                Online.index(this.$route.params.id, 'YWRtaW4=').then(response => {
                    const { id, name, duration } = response.data.test
                    this.test.id = id
                    this.test.name = name
                    this.categories = response.data.category
                    this.selected_category = { key: 1, id: this.categories[0].id, info: this.categories[0].info, name: this.categories[0].name }
                    this.total_questions = response.data.total_questions
                    this.test.settings = response.data.test_settings
                    this.test.duration = duration*60

                    if(this.test.settings.includes(12)) {
                        this.getQuestion()
                    }
                    else {
                        this.getQuestions()
                    }

                    if(this.test.settings.includes(2)) {
                        this.secureBrowser()
                    }

                    if(this.test.settings.includes(3)) {
                        this.screenShot()
                    }

                    if(this.test.settings.includes(4)) {
                        this.camera_enable = true
                        this.startWebCam()
                    }

                    if(this.test.settings.includes(5)) {
                        this.report_display = false
                    }

                    if(this.test.settings.includes(11)) {
                        this.required_all = true
                    }

                    if(!this.test.settings.includes(1)) {
                        let browser = this.getCurrentBrowser()
                        if(browser.name != "Chrome" || browser.version < 95) {
                            this.test.errors.validate = "Whoops, We can't run your tests. Please Upgrade or use Chrome Browser(greater then or equal to version 95)."
                            return false
                        }
                    }

                    if(this.test.settings.includes(9)) {
                        this.test.errors.validate = "This test has been deactivated. Please contact your administrator for assistance."
                        return false
                    }

                    this.validateTest = true
                    this.proceed = true

                    this.loader_spin = false
                }).catch(error=> {
                    if(error.response.status === 400) {
                        this.test.errors.validate = error.response.data.error
                    }
                    this.loader_spin = false
                })
            },
            async onProceedClick() {
                this.test.taker_id = ''
                this.test.taker_email = ''
                this.proceed = false
                this.test_screen = true
                this.timeCounterStart()
            },
            async sectionChanged() {
                if(confirm('Are you sure want to change the section?')) {
                    if(this.test.settings.includes(12)) {
                        this.getQuestion()
                    }
                    else {
                        this.getQuestions()
                    }
                }
            },
            async getQuestion(page = 1) {
                this.loader_spin = true
                Online.question(this.test.id, this.selected_category.id, page).then(response => {
                    this.test.question = response.data.question
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async getQuestions() {
                this.loader_spin = true
                Online.questions(this.test.id, this.selected_category.id).then(response => {
                    this.test.questions = response.data.questions
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                })
            },
            async answerSelected(question_id, question_title, question_options, correct, marks, selected, test_id, category_id, category_name) {
                var answered = [];
                answered = {'question_id': question_id, 'question': question_title, 'taker_id': this.test.taker_id, 'options': question_options, 'correct': correct, 'marks': marks, 'selected': selected, 'test_id': test_id, 'category_id': category_id, 'category': category_name}

                if(Object.keys(this.selected_answers).length > 0) {
                    this.progress_bar = Object.keys(this.selected_answers).length * 100 / this.total_questions
                }
                
                /*Online.answerSave(answered).then(response => {
                   
                }).catch(error=> { })*/
            },
            async nextSection() {
                if(confirm('Are you sure want to change the section?')) {
                    var key = this.selected_category.key + 1
                    var index = key - 1
                    this.selected_category = { key: key, id: this.categories[index].id, info: this.categories[index].info, name: this.categories[index].name }
                    if(this.test.settings.includes(12)) {
                        this.getQuestion()
                    }
                    else {
                        this.getQuestions()
                    }
                }
            },
            async screenShot() {
                var i = 1
                setInterval(function() {
                    if(this.test.taker_id && (i == 1 || i == 15 || i == 30 || i == 45)) {
                        const captureElement = document.querySelector('#kt_post')
                        let screen = html2canvas(document.body)
                        .then((canvas, test) => {
                            canvas.style.display = 'none'
                            document.body.appendChild(canvas)
                            return canvas
                        })
                        .then(canvas => {
                            this.test.screensnap = canvas.toDataURL('image/wepb', 0.5)
                            Online.takerScreenshot(this.test).then(response => {
                                canvas.remove()
                            }).catch(error=> {
                                canvas.remove()
                            })
                        })
                        i++
                    }
                }.bind(this), 30000)
            },
            async startWebCam() {
                const constraints = (window.constraints = {
                    audio: false,
                    video: { sharpness: true, pan: true, focusMode: true }
                });
                navigator.mediaDevices
                .getUserMedia(constraints)
                .then(stream => {
                   this.camera_error = ''
                   this.$refs.camera.srcObject = stream
                   this.captureImage()
                }).catch(error => {
                    if(error) {
                        this.camera_error = error
                    }
                });
            },
            async stopWebCam() {
                let tracks = this.$refs.camera.srcObject.getTracks();
                tracks.forEach(track => {
                    track.stop();
                });
            },
            async captureImage() {
                var i = 1
                setInterval(function(){    
                    if(this.test.taker_id && (i == 1 || i == 10 || i == 30 || i == 40)) {
                        const context = this.$refs.canvas.getContext('2d')
                        context.drawImage(this.$refs.camera, 0, 0, 300, 300)
                        this.test.snap = this.$refs.canvas.toDataURL("image/jpeg", 0.8)

                        Online.takerSnap(this.test).then(response => {
                            context.remove()
                        }).catch(error=> {
                            context.remove()
                        })
                        i++
                    }
                }.bind(this), 40000)
            },
            async timeCounterStart() {
                if(this.test.duration > 0) {
                    setTimeout(() => {
                        this.test.duration -= 1

                        var d = Number(this.test.duration)
                        var h = Math.floor(d / 3600);
                        var m = Math.floor(d % 3600 / 60);
                        var s = Math.floor(d % 3600 % 60);

                        var hDisplay = h > 0 ? h + (h == 1 ? "h " : "h ") : ""
                        var mDisplay = m > 0 ? m + (m == 1 ? "m " : "m ") : ""
                        var sDisplay = s > 0 ? s + (s == 1 ? "s" : "s") : ""
                        
                        this.time_counter  = hDisplay + mDisplay + sDisplay

                        this.timeCounterStart()
                    }, 1000)
                }
                else {
                    this.test_screen = false
                    this.timeoutscreen = true
                }                  
            },
            async finishTestTimeout() {
                this.updateTestStatus()
                this.timeoutscreen = false
                this.thankuscreen = true
                this.stopWebCam()
            },
            async finishTest() {
                if(confirm("Are you sure you want to finish this test?")){
                    this.updateTestStatus()
                    this.test_screen = false
                    this.thankuscreen = true
                    this.stopWebCam()
                }
            },
            async updateTestStatus() {
                this.loader_spin = false
                this.test.preview = true
                /*Online.updateStatus(this.test.taker_id, this.test).then(response => {
                }).catch(error=> {
                    this.loader_spin = false
                })*/
            },
            async secureBrowser() {
                document.addEventListener('keydown', function(e) {
                    if(e.ctrlKey && e.keyCode == 73) {
                        e.preventDefault()
                    }
                    if(e.ctrlKey && e.keyCode == 67) {
                        e.preventDefault()
                    }
                    if(e.keyCode == 123) {
                        e.preventDefault()
                    }
                }, false)
            },
            getCurrentBrowser() {
                var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []

                if(/trident/i.test(M[1])) {
                    tem=/\brv[ :]+(\d+)/g.exec(ua) || []
                    return {name:'IE',version:(tem[1]||'')}
                }   
                if(M[1]==='Chrome') {
                    tem=ua.match(/\bOPR|Edge\/(\d+)/)
                    if(tem!=null)   {return {name:'Opera', version:tem[1]};}
                }   
                M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?']
                
                if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
                return {
                  name: M[0],
                  version: M[1]
                };
            }
        }
    }
</script>