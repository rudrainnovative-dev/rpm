<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Test Performance</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small fs-6">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link small fs-6">Test</router-link>
                        </li>
                         <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"ReportList"}' class="text-link small fs-6">Test Performance</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small fs-6">Test Report</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row">
                    <div class="col-md-6 col-12 mb-2">
                        <div class="input-group mb-3">
                            <input type="text" :value="share_link" class="form-control form-control-solid form-control-sm" ref="share_link" :readonly="true"/>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-sm btn-secondary" v-on:click="copyShareLink">Copy Share Link</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 text-right mb-2">
                        <router-link :to='{name:"ReportList"}' class="btn btn-sm btn-secondary ml-4">Back</router-link>
                    </div>
                </div>
                <div class="row" v-if="report">
                    <div class="col-md-12 col-12 d-flex flex-wrap">
                        <div class="card card-custom bg-primary w-100 mb-lg-8 mb-6">
                            <div class="card-header border-0">
                                <div class="card-title m-0">
                                    <span class="svg-icon svg-icon-white svg-icon-2x me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"></rect>
                                                <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"></rect>
                                            </g>
                                        </svg>
                                    </span>
                                    <h3 class="card-label text-white m-0 fw-bolder">{{ report.test_name }}</h3>
                                </div>
                            </div>
                            <div class="separator separator-solid separator-white opacity-20"></div>
                            <div class="card-body text-white">
                                <div class="row m-0">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group row align-items-center">
                                            <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase fw-normal ls-1">Name</small></label>
                                            <p class="m-0 fs-5">{{ report.name }}</p>
                                        </div>
                                        <div class="my-6 w-100"></div>
                                        <div class="form-group row align-items-center">
                                            <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase fw-normal ls-1">email address</small></label>
                                            <p class="m-0 fs-5">{{ report.email }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <div class="form-group row align-items-center">
                                            <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase fw-normal ls-1">test taken</small></label>
                                            <p class="m-0 fs-5">{{ report.created_at | formatDate }}</p>
                                        </div>
                                        <div class="my-6 w-100"></div>
                                        <div class="form-group row align-items-center">
                                            <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase fw-normal ls-1">finish state</small></label>
                                            <p class="m-0 fs-5" v-if="report.status === 2">Normal</p>
                                            <p class="m-0 fs-5 text-warning" v-if="report.status === 1">IN Progress</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion w-100" id="accordionExample">
                            <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button fw-bolder fs-6" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Registration Details
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="row m-0">
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase">Name</small></label>
                                                    <p class="m-0 fw-bolder fs-5">{{ report.name }}</p>
                                                </div>
                                            </div>                                                    
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase">Email</small></label>
                                                    <p class="m-0 fw-bolder fs-5">{{ report.email }}</p>
                                                </div>
                                            </div>                                                      
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase">Contact no.</small></label>
                                                    <p class="m-0 fw-bolder fs-5" v-if="report.mobile">{{ report.mobile }}</p>
                                                    <p class="m-0 fw-bolder fs-5" v-else>Not Filled</p>
                                                </div>
                                            </div>
                                            <div class="w-100 my-5"></div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase">Date of Birth</small></label>
                                                    <p class="m-0 fw-bolder fs-5" v-if="report.dob">{{ report.dob }}</p>
                                                    <p class="m-0 fw-bolder fs-5" v-else>Not Filled</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase">Gender</small></label>
                                                    <p class="m-0 fw-bolder fs-5" v-if="report.gender">{{ report.gender }}</p>
                                                    <p class="m-0 fw-bolder fs-5" v-else>Not Filled</p>
                                                </div>
                                            </div>
                                            <div class="w-100 my-5"></div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0 mb-2"><small class="fw-bold text-uppercase">profile picture snapshot</small></label>
                                                    <img :src="report.avatar" alt="profile" class="img-fluid mw-75" v-if="report.avatar">
                                                    <img :src="dummy_image" alt="profile" class="img-fluid mw-75" v-else>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12">
                                                <div class="form-group row align-items-center">
                                                    <label for="section" class="opacity-75 py-0 mb-2"><small class="fw-bold text-uppercase">identity card snapshot</small></label>
                                                    <img :src="report.id_card" alt="profile" class="img-fluid mw-75" v-if="report.id_card">
                                                    <img :src="dummy_image" alt="profile" class="img-fluid mw-75" v-else>
                                                </div>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Performance Summary</button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <p class="m-0">General Aptitude is defined as   potential   of the candidate and    the ability to grasp new concepts   which   are crucial to his/ her role as an employee in the organization. It is the innate, learned or   acquired ability of an  individual to   perform certain tasks. It   helps   assess Individual's capacity for learning in general regardless of   any specific skill.</p>
                                        <highcharts :options="chartOptions" class="my-5"></highcharts>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden" v-if="selected_sections.includes(3)">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Reasoning Ability</button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <p class="m-0">The ability of an individual to analyse and perceive the given   information from different perspectives.</p>
                                        <highcharts :options="reasoningChartOptions" class="my-5"></highcharts>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden" v-if="selected_sections.includes(2)">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">Numerical Ability</button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <p class="m-0">The ability to perceive and process numbers and related symbols to perform basic arithmetic operations.</p>
                                        <highcharts :options="numberChartOptions" class="my-5"></highcharts>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden" v-if="selected_sections.includes(1)">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">Attention analysis</button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <p class="m-0">The ability to structure observations that are obtained by  different data sources.</p>
                                        <highcharts :options="attentionChartOptions" class="my-5"></highcharts>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden" v-if="selected_sections.includes(4)">
                                <h2 class="accordion-header" id="headingSix">
                                    <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        Verbal Ability
                                    </button>
                                </h2>
                                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <p class="m-0">The ability to understand word meanings, word relationships and  also interpret detailed information.</p>
                                        <highcharts :options="verbalChartOptions" class="my-5"></highcharts>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item rounded border-0 shadow-sm overflow-hidden">
                                <h2 class="accordion-header" id="headingSeven">
                                    <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">Test Log</button>
                                </h2>
                                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="row m-0" v-if="avatars.length > 0">
                                            <div class="col-md-12">
                                                <h6 class="mb-3">Images of Test Taker</h6>
                                            </div>                                                   
                                            <div class="col-md-6 col-12" v-for="avatar in avatars">
                                                <div class="form-group align-items-center">
                                                    <img :src="avatar.snap" alt="image" class="border border-secondary rounded img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4" v-if="screenshots.length > 0">
                                            <div class="col-md-12">
                                                <h6 class="mb-3">Screenshot of Test Taker</h6>
                                            </div>                                                   
                                            <div class="col-md-6 col-12" v-for="screenshot in screenshots">
                                                <div class="form-group align-items-center">
                                                    <img :src="screenshot.snap" alt="image" class="border border-secondary rounded img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <h6 class="mb-3">{{ report.created_at | formatDate }}</h6>
                                            </div>                                                   
                                            <div class="col-md-12 col-12">
                                                <p><span class="text-muted">{{ report.created_at | formatTime }}</span> Started the test.</p>
                                                <p v-if="avatars.length > 0"><span class="text-muted">{{ report.created_at | formatTime }}</span> Candidate gave us right to the following feeds: camera, microphone.</p>
                                                <p v-for="(time, index) in logs" v-if="Object.keys(logs).length > 0">
                                                    <span class="text-muted">{{ time | formatTime }}</span> Went to {{ categories[index] }} of the test. 
                                                </p>
                                                <p v-if="report.status === 2"><span class="text-muted">{{ report.updated_at | formatTime }}</span> Finished the test.</p>
                                            </div>
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
import Report from "../../apis/Report"
import {Chart} from 'highcharts-vue'

export default {
    name:"questions",
    components: {
        highcharts: Chart 
    },
    data() {
        return {
            report: {},
            dummy_image: '../../../img/dummy-img.png',
            loader_spin: false,
            chartOptions: {
                chart: {
                    height: 140,
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['Cognitive Abilities']
                },
                yAxis: {
                    min: 0,
                    max:100,
                    title: {
                      text: 'Values shown in above chart are percentages'
                    }
                },
                legend: {
                    enabled: true,
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                credits: {
                    enabled: false
                },
                series: []
            },
            reasoningChartOptions: {
                chart: {
                    height: 140,
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['Reasoning Abilities']
                },
                yAxis: {
                    min: 0,
                    max:100,
                    title: {
                      text: 'Values shown in above chart are percentages'
                    }
                },
                legend: {
                    enabled: true,
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                credits: {
                    enabled: false
                },
                series: []
            },
            numberChartOptions: {
                chart: {
                    height: 140,
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['Numerical Abilities']
                },
                yAxis: {
                    min: 0,
                    max:100,
                    title: {
                      text: 'Values shown in above chart are percentages'
                    }
                },
                legend: {
                    enabled: true,
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                credits: {
                    enabled: false
                },
                series: []
            },
            attentionChartOptions: {
                chart: {
                    height: 140,
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['Attention Abilities']
                },
                yAxis: {
                    min: 0,
                    max:100,
                    title: {
                      text: 'Values shown in above chart are percentages'
                    }
                },
                legend: {
                    enabled: true,
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                credits: {
                    enabled: false
                },
                series: []
            },
            verbalChartOptions: {
                chart: {
                    height: 140,
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: ['Verbal Abilities']
                },
                yAxis: {
                    min: 0,
                    max:100,
                    title: {
                      text: 'Values shown in above chart are percentages'
                    }
                },
                legend: {
                    enabled: true,
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                credits: {
                    enabled: false
                },
                series: []
            },
            performance: {},
            avatars: [],
            screenshots: [],
            categories: [],
            logs: [],
            selected_sections: [],
            share_link: '',
        }
    },
    mounted(){
        this.getReports()
    },
    methods:{
        async getReports(page = 1) {
            this.loader_spin = true
            Report.show(this.$route.params.id).then(response => {
                const { taker, performance, categories, sections, correct_sections, avatars, screenshots, logs } = response.data
            
                this.report = taker
                this.share_link = window.location.origin+'/share-report?key='+this.report.key
                var total = this.report.correct_marks*100/this.report.total_marks
                
                total = Math.ceil(total)
                
                var label = ''
                this.performance = performance
                this.performance.forEach(element => {
                    if(total >= element.min && total <= element.max) {
                        label = element.name;
                    }
                })

                var color = this.colors(label)
                var Obj = { name: label, data: [total], color: color }
                this.chartOptions.series.push(Obj)
                
                for(var i = 1; i <= Object.keys(categories).length; i++) {
                    
                    let marks_total = 0
                    let section_label = 'Very Low'

                    if(correct_sections[i]) {
                       marks_total = correct_sections[i] * 100 / sections[i]
                       marks_total = Math.ceil(marks_total)
                    }

                    this.performance.forEach(element => {
                        if(marks_total >= element.min && marks_total <= element.max) {
                            section_label = element.name;
                        }
                    })

                    let section_color = this.colors(section_label)

                    if(i == 1) {
                        var AttObj = { name: section_label, data: [marks_total], color: section_color }
                        this.attentionChartOptions.series.push(AttObj)
                    }

                    if(i == 2) {
                        var NumObj = { name: section_label, data: [marks_total], color: section_color }
                        this.numberChartOptions.series.push(NumObj)
                    }

                    if(i == 3) {
                        var ResObj = { name: section_label, data: [marks_total], color: section_color }
                        this.reasoningChartOptions.series.push(ResObj)
                    }

                    if(i == 4) {
                        var VerObj = { name: section_label, data: [marks_total], color: section_color }
                        this.verbalChartOptions.series.push(VerObj)
                    }

                    if(sections[i]) {
                        this.selected_sections.push(i)
                    }
                }

                this.avatars = avatars
                this.screenshots = screenshots
                this.categories = categories
                this.logs = logs
                this.loader_spin = false
            })
            .catch(error=> {
                this.report = []
                this.loader_spin = false
            });
        },
        colors(val) {
            if(val == 'Very Low') {
                return '#ff0000'
            }
            else if(val == 'Low') {
                return '#cd5757'
            }
            else if(val == 'Very High') {
                return '#5345a1'
            }
            else if(val == 'High') {
                return '#2710a5'
            }
            else if(val == 'Moderate') {
                return '#28af51'
            }
            else if(val == 'Excellent') {
                return '#50cd89'
            }
            else {
                return '#50cd89'
            }
        },
        copyShareLink() {
            var url = this.$refs.share_link;
            url.innerHTML = window.location.href;
            url.select();
            document.execCommand("copy");
            this.$toast.success('Share Link Copied!');
        }
    }
}
</script>