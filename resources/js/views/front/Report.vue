<template>
    <div class="row" v-show="show_report">
        <div class="col-md-12 col-12">
             <div class="alert alert-success text-center">
                <h4 v-if="after_message">{{ after_message }}</h4>
                <h4 v-else>Thank You!</h4>
                <span class="d-block">Your result as following:</span>
            </div>
        </div>
        <div class="col-md-12 col-12 mt-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="m-0 fs-6">{{ report.test_name }}</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="m-0 fs-6">Score: <span class="text-muted">{{ Math.ceil(report.correct_marks*100/report.total_marks) }} %</span></p>
                        </div>
                        <div class="col-md-4 text-right">
                            <p class="m-0 fs-6 mr-5">Time Taking: <span class="text-muted">{{ timeduration }} Min.</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-12 mt-8">
            <div class="card">
                <div class="card-body">
                    <p class="fs-6 font-weight-bold mb-4">Performance Summary</p>
                    <p class="m-0">General Aptitude is defined as   potential   of the candidate and    the ability to grasp new concepts   which   are crucial to his/ her role as an employee in the organization. It is the innate, learned or   acquired ability of an  individual to   perform certain tasks. It   helps   assess Individual's capacity for    learning in general regardless of   any specific skill.</p>
                    <highcharts :options="chartOptions" class="my-5"></highcharts>
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
import {Chart} from 'highcharts-vue'

export default {
    name:"test-taker-report",
    props: ['taker', 'after_message'],
    components: {
        highcharts: Chart 
    },
    data() {
        return {
            loader_spin: true,
            report: {},
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
            performance: {},
            timeduration: '',
            show_report: false
        }
    },
    mounted(){
        this.getReport()
    },
    methods:{
        async getReport() {
            Online.report(this.taker).then(response => {
                const { taker, performance, categories, sections, correct_sections } = response.data
                this.report = taker
                var total = this.report.correct_marks*100/this.report.total_marks
                total = Math.ceil(total)

                var fromTime = new Date(this.report.created_at)
                var toTime = new Date(this.report.updated_at)

                var diffMs = (toTime - fromTime)
                this.timeduration = Math.round(((diffMs % 86400000) % 3600000) / 60000)

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
                this.show_report = true
                this.loader_spin = false
            })
            .catch(error=> {
                this.report = []
                this.loader_spin = false
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
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
        }
    }
}
</script>