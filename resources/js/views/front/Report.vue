<template>
    <div class="row">
        <div class="col-md-12 col-12">
             <div class="alert alert-success text-center">
                <h4>Thank you for taking the time to complete Test.</h4>
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
    props: ['taker'],
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
                

                var color = this.colors(total)
                var label = ''
                this.performance = performance
                this.performance.forEach(element => {
                    if(total >= element.min && total <= element.max) {
                        label = element.name;
                    }
                })
                
                var Obj = { name: label, data: [total], color: color }
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