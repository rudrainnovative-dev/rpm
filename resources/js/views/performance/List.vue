<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Performance Criteria</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-muted small">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 small">Performance Criteria</p>
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
                            <div class="card-header border-0">
                                <h3 class="card-title fw-bolder text-dark m-0">History Performance Criteria</h3>
                                <div class="d-flex align-items-center py-1">
                                    <router-link :to='{name:"PerformanceAdd"}' class="btn btn-sm btn-primary me-4">Set Criteria</router-link>
                                </div>
                            </div>
                            
                            <div class="separator mb-2"></div>
                            <div class="card-body">
                                <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                                    <thead>
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                            <th class="fw-bolder align-middle">Valid From</th>
                                            <th class="fw-bolder align-middle">Valid To</th>
                                            <th class="fw-bolder align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="performanceData.length > 0">
                                        <tr v-for="(performance,key) in performanceData" :key="key">
                                            <td class="align-middle">{{ performance.from }}</td>
                                            <td class="align-middle">{{ performance.to }}</td>
                                            <td class="align-middle">
                                                <ul class="list-unstyled list-inline m-0" v-if="performance.id > 0">
                                                    <li class="list-inline-item mb-2">
                                                        <router-link :to='{name:"PerformanceEdit",params:{id:performance.id}}'><button class="btn btn-sm btn-light-warning p-0 text-center h-30px w-30px" type="button"><i class="p-0 fa fa-edit"></i></button></router-link>
                                                    </li>
                                                    <li class="list-inline-item mb-2">
                                                        <button class="btn btn-sm btn-light-danger p-0 text-center h-30px w-30px" type="button" @click="deletePerformance(performance.id)"><i class="p-0 fa fa-trash"></i></button>
                                                    </li>
                                                </ul>
                                                <ul class="list-unstyled list-inline m-0" v-else>
                                                    <li class="list-inline-item mb-2">
                                                        <span class="badge badge-light-success">Default</span>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-if="performanceData.length == 0">
                                        <tr>
                                            <td colspan = '3'>No any performance criteria(s).</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="col-md-12 col-12">
                                    <div class="d-flex justify-content-end align-items-center flex-wrap">
                                        <pagination :data="performance" :limit="2" @pagination-change-page="getPerformance"></pagination>
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
import Performance from "../../apis/Performance";

export default {
    name:"performance",
    data() {
        return {
            performance:{},
            performanceData: [],
            loader_spin: true
        }
    },
    mounted(){
        this.getPerformance()
    },
    methods:{
        async getPerformance(page = 1) {
          Performance.index(page).then(response => {
            this.performance = response.data.performance_criterias;
            this.performanceData = response.data.performance_criterias.data;
            this.loader_spin = false
          })
          .catch(error=> {
            this.performance = []
            this.loader_spin = false
          });
        },
        deletePerformance(id) {
            if(confirm("Are you sure to delete this criteria ?")){
                Performance.delete(id).then(response => {
                    this.getPerformance()
                    this.$toast.success(response.data.message);
                }).catch(error=>{
                    this.loader_spin = false 
                });
            }
        }
    }
}
</script>