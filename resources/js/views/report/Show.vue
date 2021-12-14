<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Test Performance</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">Test Performance</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
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
                                            <p class="m-0 fs-5"></p>
                                        </div>
                                        <div class="my-6 w-100"></div>
                                        <div class="form-group row align-items-center">
                                            <label for="section" class="opacity-75 py-0"><small class="fw-bold text-uppercase fw-normal ls-1">finish state</small></label>
                                            <p class="m-0 fs-5">Normal</p>
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
                                            <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                Performance Summary
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
                                            <div class="accordion-body">
                                                <p class="m-0">General Aptitude is defined as   potential   of the candidate and    the ability to grasp new concepts   which   are crucial to his/ her role as an employee in the organization. It is the innate, learned or   acquired ability of an  individual to   perform certain tasks. It   helps   assess Individual's capacity for    learning in general regardless of   any specific skill.</p>

                                                <highcharts :options="chartOptions" class="my-5"></highcharts>
                                                
                                                <div class="my-5 text-center">
                                                    <span :style="{width:18px;height:18px;}" class="border"></span><span >{{ label }} : {{ total }}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                        <!-- performance summary ends -->

                                        <!-- reasoning ability -->
                                        <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    Reasoning Ability
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">

                                                    <p class="m-0">The ability of   an individual   to analyse and perceive the given   information from different perspectives.</p>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- reasoning ability ends -->

                                        <!-- numerical ability -->
                                        <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden">
                                            <h2 class="accordion-header" id="headingFour">
                                                <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                    Numerical Ability
                                                </button>
                                            </h2>
                                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">

                                                    <p class="m-0">The ability to   perceive and process numbers and related symbols    to perform basic arithmetic operations.</p>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- numerical ability ends -->

                                        <!-- data analysis -->
                                        <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden">
                                            <h2 class="accordion-header" id="headingFive">
                                                <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                                    Data analysis
                                                </button>
                                            </h2>
                                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">

                                                    <p class="m-0">The ability to   structure   observations that   are obtained    by  different   data sources.</p>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- data analysis ends -->

                                        <!-- verbal ability -->
                                        <div class="accordion-item mb-lg-6 mb-5 rounded border-0 shadow-sm overflow-hidden">
                                            <h2 class="accordion-header" id="headingSix">
                                                <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                                    Verbal Ability
                                                </button>
                                            </h2>
                                            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">

                                                    <p class="m-0">The ability to   understand word meanings,   word relationships and  also interpret detailed information.</p>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- verbal ability ends -->

                                        <!-- test log -->
                                        <div class="accordion-item rounded border-0 shadow-sm overflow-hidden">
                                            <h2 class="accordion-header" id="headingSeven">
                                                <button class="accordion-button fw-bolder fs-6 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                                    Test Log
                                                </button>
                                            </h2>
                                            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample" style="">
                                                <div class="accordion-body">                                                

                                                    <div class="row m-0">

                                                        <div class="col-md-12">
                                                            <h6 class="mb-3">Images of the Test Taker</h6>
                                                        </div>

                                                        <!-- first -->                                                      
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group row align-items-center">
                                                                <img src="img/dummy-img.png" alt="image" class="img-fluid mw-75">
                                                            </div>
                                                        </div>
                                                        <!-- first ends -->

                                                        <!-- second -->                                                     
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group row align-items-center">
                                                                <img src="img/dummy-img.png" alt="image" class="img-fluid mw-75">
                                                            </div>
                                                        </div>
                                                        <!-- second ends -->

                                                        <!-- 3rd -->                                                        
                                                        <div class="col-md-4 col-12">
                                                            <div class="form-group row align-items-center">
                                                                <img src="img/dummy-img.png" alt="image" class="img-fluid mw-75">
                                                            </div>
                                                        </div>
                                                        <!-- 3rd ends -->

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- test log ends -->

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
                    enabled: false,
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
            label: '',
            color: '',
            total: '',
            performance: {}
        }
    },
    mounted(){
        this.getReports()
    },
    methods:{
        async getReports(page = 1) {
            this.loader_spin = true
            Report.show(this.$route.params.id).then(response => {
                
                const { taker, performance, categories } = response.data

                this.report = taker

                this.total = this.report.correct_marks*100/this.report.total_marks
                this.color = this.colors(this.total)

                this.performance = performance
                this.performance.forEach(element => {
                    if(this.total > element.min && this.total < element.max) {
                        this.label = element.name;
                    }
                })
                
                var Obj = { name: this.label, data: [this.total], color: this.color }
                console.log(Obj)
                this.chartOptions.series.push(Obj)
                
                this.loader_spin = false
            })
            .catch(error=> {
                this.report = []
                this.loader_spin = false
            });
        },
        colors(val) {
            if(val <= 40) {
                return '#ff0000'
            }
            else if(val > 41 && val <= 60) {
                return '#7239ea'
            }
            else if(val > 61 && val <= 80) {
                return '#ffc700'
            }
            else {
                return '#50cd89'
            }
        }
    }
}
</script>