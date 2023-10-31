<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="toolbar" id="kt_toolbar">
         <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title  me-3 mb-5 mb-lg-0 lh-1">
               <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">History</h1>
               <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"PerformanceAdd"}' class="text-link small fs-6">Set Performance Criteria</router-link>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <p class="text-muted m-0 small fs-6">History</p>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="post d-flex flex-column-fluid" id="kt_post">
         <div id="kt_content_container" class="container">
            <div class="row">
               <div class="col-md-12 col-12">
                  <div class="d-flex align-items-center justify-content-md-end mb-4 mt-7">
                     <div class="badge-custom me-3">
                        <span class="badge badge-light-primary fw-normal p-3">Currently v{{ this.$route.params.id }}.0</span>
                        <span class="small m-0 text-muted" v-if="fromdate !== todate">(from {{ fromdate | formatDate }} to {{ todate | formatDate }})</span>
                        <span class="small m-0 text-muted" v-else>(from {{ fromdate | formatDate }} to Till Now)</span>
                     </div>
                  </div>
               </div>
              <div class="col-md-12 col-12 d-flex">
                  <div class="card card-xl-stretch w-100">
                     <div class="card-body">
                        <div class="row mb-3" v-for="type in performance_type">
                           <div class="col-md-12 col-12 py-3">
                              <h5 class="fw-bolder fs-3 text-capitalize">{{ type.name }}</h5>
                           </div>
                           <div class="row" v-for="(rows, index) in performance.data" :key="index" v-if="type.id == index">
                              <div class="col-md-12 col-12" v-for="(row, indx) in rows">
                                 <div class="alert alert-dismissible fade show position-relative p-0" role="alert">
                                    <div class="row">
                                       <p class="h5 text-center my-4" v-if="indx > 0">Or</p>
                                       <div class="col-md-4 col-12">
                                          <div class="form-group">
                                             <label class="w-100 mb-2 fw-bold">Criteria Type</label>
                                             <select class="form-control form-control-solid form-control-sm" :name="row.criteria" v-model="row.criteria" disabled>
                                               <option :value="'Section'">Section</option>
                                               <option :value="'Overall'">Overall</option>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col-md-4 col-12">
                                          <div class="form-group">
                                             <label class="w-100 mb-2 fw-bold">Formula Value</label>
                                             <select class="form-control form-control-solid form-control-sm" :name="row.formula" v-model="row.formula" disabled>
                                                <option :value="'>'">Greater Than</option>
                                                <option :value="'='">Equal Than</option>
                                                <option :value="'<'">Less Than</option>
                                             </select>
                                         </div>
                                       </div>
                                       <div class="col-md-4 col-12">
                                          <div class="form-group">
                                             <label class="w-100 mb-2 fw-bold">Score Percentage<small class="text-danger">*</small></label>
                                             <input type="number" class="form-control form-control-solid form-control-sm" :name="row.score" v-model="row.score" placeholder="Enter a value" min = '0' disabled>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-12 col-12 mt-5">
                                 <label class="w-100 mb-2">Remarks</label>
                                 <textarea class="form-control form-control-solid form-control-sm" rows="3" :name="performance.remarks[type.id]" v-model="performance.remarks[type.id]" placeholder="Start typing here"></textarea>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12 col-12 text-md-end">
                           <ul class="list-unstyled list-inline m-0">
                              <li class="list-inline-item">
                                 <router-link :to='{name:"PerformanceAdd"}' class="btn btn-dark btn-sm">Go to Current Version</router-link>
                              </li>
                           </ul>
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
   name:"show-performance",
   data(){
      return {
         performance: {
            data: [],
            remarks: []
         },
         performance_type:[],
         current_performance: [],
         fromdate: '',
         todate: '',
         disabled: false,
         loader_spin: true
      }
   },
   mounted() {
      this.getPerformance()
   },
   methods:{
      async getPerformance() {
         Performance.show(this.$route.params.id).then(response => {
            this.performance_type = response.data.performance_type
            this.current_performance = response.data.current_performance

            this.fromdate = this.current_performance.created_at
            this.todate = this.current_performance.updated_at
            var ObjPerformance = {}
            var ObjRemarks = {}

            this.current_performance.options.forEach(element => {
               var Obj = { criteria: element.criteria, formula: element.formula, score: element.score }
               var type_id = element.performance_type_id;

               if(!ObjPerformance[type_id]) {
                  ObjPerformance[type_id] = []
               }
               ObjPerformance[type_id].push(Obj)

               ObjRemarks[type_id] = element.remarks
            })

            this.performance.data = ObjPerformance
            this.performance.remarks = ObjRemarks

            this.loader_spin=false
         })
         .catch(error=> {
            this.loader_spin=false
            if (error.response.status === 401) {
               this.$toast.error(error.response.data.message);
               this.$router.push({ name: "Login" });
            }
         });
      }
   }
}
</script>
