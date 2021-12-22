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
                <div class="row">
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch w-100">
                            <div class="card-body">
                                <ul class="nav nav-tabs nav-tabs-line">
                                    <li class="nav-item">
                                        <button :class="tab1?'nav-link active':'nav-link'" v-on:click="tabsClick('tab1')">Completed</button>
                                    </li>
                                    <li class="nav-item">
                                        <button :class="tab2?'nav-link active':'nav-link'"  v-on:click="tabsClick('tab2')">In Progress</button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-5" id="myTabContent">
                                    <div class="tab-pane fade active show" v-if="tab1">
                                        <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                    <th class="fw-bolder align-middle">Name</th>
                                                    <th class="fw-bolder align-middle">Test Date</th>
                                                    <th class="fw-bolder align-middle">Score(%)</th>
                                                    <th class="fw-bolder align-middle">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="reports.data && Object.keys(reports.data).length > 0">
                                                <tr v-for="(report, key) in reports.data" :key="key">
                                                    <td>
                                                        <span class="d-block">{{ report.name }}</span>
                                                        <span class="d-block text-muted">{{ report.email }}</span>
                                                    </td>
                                                    <td>{{ report.created_at | formatDate }}</td>
                                                    <td v-if="report.status == 2">{{ Math.ceil(report.correct_marks * 100 / report.total_marks) }}</td>
                                                    <td v-else>-</td>
                                                    <td>
                                                        <ul class="list-unstyled list-inline m-0">
                                                            <li class="list-inline-item mb-2" v-tooltip="'Download Report'">
                                                                <button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" @click="downloadReport(report.id)"><i class="p-0 fa fa-download"></i></button>
                                                            </li>
                                                            <li class="list-inline-item mb-2" title="View" v-tooltip="'View Report'">
                                                                <router-link :to='{name:"ReportShow",params:{id:report.id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button"><i class="p-0 fa fa-eye"></i></button>
                                                                </router-link>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td>No any record(s).</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 col-12">
                                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                                <pagination :data="reports" :limit="2" @pagination-change-page="getReports"></pagination>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade active show" v-if="tab2">
                                        <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                                            <thead>
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                    <th class="fw-bolder align-middle">Name</th>
                                                    <th class="fw-bolder align-middle">Test Date</th>
                                                    <th class="fw-bolder align-middle">Score(%)</th>
                                                    <th class="fw-bolder align-middle">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody v-if="processing.data && Object.keys(processing.data).length > 0">
                                                <tr v-for="(report, key) in processing.data" :key="key">
                                                    <td>
                                                        <span class="d-block">{{ report.name }}</span>
                                                        <span class="d-block text-muted">{{ report.email }}</span>
                                                    </td>
                                                    <td>{{ report.created_at | formatDate }}</td>
                                                    <td v-if="report.status == 2">{{ Math.ceil(report.correct_marks * 100 / report.total_marks) }}</td>
                                                    <td v-else>-</td>
                                                    <td>
                                                        <ul class="list-unstyled list-inline m-0">
                                                            <li class="list-inline-item mb-2" v-tooltip="'Download Report'">
                                                                <button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" @click="downloadReport(report.id)"><i class="p-0 fa fa-download"></i></button>
                                                            </li>
                                                            <li class="list-inline-item mb-2" title="View" v-tooltip="'View Report'">
                                                                <router-link :to='{name:"ReportShow",params:{id:report.id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button"><i class="p-0 fa fa-eye"></i></button>
                                                                </router-link>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody v-else>
                                                <tr>
                                                    <td>No any record(s).</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 col-12">
                                            <div class="d-flex justify-content-end align-items-center flex-wrap">
                                                <pagination :data="processing" :limit="2" @pagination-change-page="getReports"></pagination>
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
import Report from "../../apis/Report";
import axios from 'axios'

export default {
    name:"questions",
    data() {
        return {
            reports: {},
            processing: {},
            loader_spin: false,
            tab1: true,
            tab2: false,
        }
    },
    mounted(){
        this.getReports()
    },
    methods:{
        async getReports(page = 1) {
            this.loader_spin = true
            Report.index(this.$route.params.id, page).then(response => {
                this.reports = response.data.takers
                this.processing = response.data.processing
                this.loader_spin = false
            })
            .catch(error=> {
                this.reports = []
                this.loader_spin = false
            });
        },
        async downloadReport(id) {
            this.loader_spin = true
            Report.pdf(this.$route.params.id).then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'report_'+id+'.pdf');
                document.body.appendChild(link);
                link.click();
                this.loader_spin = false
                this.$toast.success('Pdf download successfully.');
            })
            .catch(error=> {
                this.loader_spin = false
            });
        },
        async tabsClick(tab) {

            if(tab == 'tab1') {
                this.tab2 = false
                this.tab1 = true
            }

            if(tab == 'tab2') {
                this.tab1 = false
                this.tab2 = true
            }
        }
    }
}
</script>