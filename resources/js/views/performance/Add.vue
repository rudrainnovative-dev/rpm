<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="toolbar" id="kt_toolbar">
         <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title  me-3 mb-5 mb-lg-0 lh-1">
               <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Set Performance Criteria</h1>
               <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Dashboard"}' class="text-link fs-6">Dashboard</router-link>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <p class="text-muted m-0 fs-6">Set Performance Criteria</p>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="post d-flex flex-column-fluid" id="kt_post">
         <div id="kt_content_container" class="container">
            <div class="row">
               <div class="col-md-12 col-12">
                  <div class="d-flex align-items-center justify-content-md-end mb-4">
                     <div class="badge-custom me-3">
                        <span class="badge badge-light-primary fw-normal p-3">Currently v{{ version }}</span>
                     </div>
                     <div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                        <div class="dropdown">
                           <button type="button" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-sm btn-primary" :class="(this.performance_history.length == 0)?'disabled':''">See Older Versions<i class="fa fa-angle-down ms-2 p-0"></i></button>
                           <ul aria-labelledby="" role="menu" class="dropdown-menu p-0 w-100 overflow-hidden z-index-1" style="">
                              <li class="dropdown-item" v-for="history in this.performance_history">
                                 <router-link :to='{name:"PerformanceShow", params: { id: history.id }}' class="d-block text-dark fw-normal px-4 py-2 fw-normal">v{{history.id}}.0</router-link>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
              <div class="col-md-12 col-12 d-flex">
                  <div class="card card-xl-stretch w-100">
                     <div class="card-body">
                        <form @submit.prevent="create">
                           <div class="row mb-3" v-for="type in performance_type">
                              <div class="col-md-12 col-12 py-3">
                                 <h5 class="fw-bolder fs-3 text-capitalize">{{ type.name }}</h5>
                              </div>
                              <div class="row" v-for="(rows, index) in performance.data" :key="index" v-if="type.id == index">
                                 <div class="col-md-12 col-12" v-for="(row, indx) in rows"> 
                                    <div class="'alert'.indx alert-dismissible fade show position-relative p-0" role="'alert'.indx">
                                       <div class="row">
                                          <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-dark bg-image-none btn-close bottom-0 end-0 top-unset opacity-100" data-bs-dismiss="'alert'.indx" aria-label="Close" v-if="indx > 0" v-on:click="removeRow(type.id, indx)">
                                             <i class="fa fa-minus"></i>
                                          </a>
                                          <p class="h5 text-center my-4" v-if="indx > 0">Or</p>
                                          <div class="col-md-4 col-12">
                                             <div class="form-group">
                                                <label class="w-100 mb-2 fw-bold">Criteria Type<small class="text-danger">*</small></label>
                                                <select class="form-control form-control-solid form-control-sm" :name="row.criteria" v-model="row.criteria" required>
                                                  <option :value="'Section'">Section</option>
                                                  <option :value="'Overall'">Overall</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="col-md-4 col-12">
                                             <div class="form-group">
                                                <label class="w-100 mb-2 fw-bold">Formula Value<small class="text-danger">*</small></label>
                                                <select class="form-control form-control-solid form-control-sm" :name="row.formula" v-model="row.formula" required>
                                                   <option :value="'>'">Greater Than</option>
                                                   <option :value="'='">Equal Than</option>
                                                   <option :value="'<'">Less Than</option>
                                                </select>
                                            </div>
                                          </div>
                                          <div class="col-md-4 col-12">
                                             <div class="form-group">
                                                <label class="w-100 mb-2 fw-bold">Score Percentage<small class="text-danger">*</small></label>
                                                <input type="number" class="form-control form-control-solid form-control-sm" :name="row.score" v-model="row.score" placeholder="Enter a value"  v-on:keyup="validateRange" required>
                                                <small class="text-muted">You can enter value from 0 to 100.</small>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-12 col-12 mt-5 justify-content-end" v-if="type.name == 'Moderate'">
                                    <button class="btn btn-dark btn-sm" type="button" v-on:click="addRow(type.id)">Add</button>
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
                                    <button type="submit" class="btn btn-dark btn-sm">Save &amp; Create New Version</button>
                                 </li>
                                 <li class="list-inline-item">
                                    <router-link :to='{name:"Dashboard"}' class="btn btn-light btn-sm">Cancel</router-link>
                                 </li>
                              </ul>
                           </div>
                        </form>
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
    name:"add-performance",
    data(){
        return {
            performance: {
               data: [],
               remarks: []
            },
            performance_type:[],
            version: '',
            performance_history: [],
            current_performance: [],
            disabled: false,
            loader_spin: true
        }
    },
    mounted() {
      this.getPerformance()
    },
    methods:{
      async create(){
         this.disabled = false
         Performance.add(this.performance).then(response => {
            this.$toast.success(response.data.message);
            this.getPerformance()
            this.disabled = false
         }).catch(error=> {
             this.disabled = false
            if (error.response.status === 401) {
               this.$toast.error(error.response.data.message);
               this.$router.push({ name: "Login" });
            }
         })
      },
      async getPerformance() {
         Performance.index().then(response => {
            this.performance_type = response.data.performance_type
            this.performance_history = response.data.performance_criterias
            this.current_performance = response.data.current_performance
                        
            if(this.current_performance) {
               this.version = this.current_performance.id + '.0'

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
            }
            else {
               this.version = '0.0'
               var ObjPerformance = {}
               this.performance_type.forEach((val, index) => {
                  var Obj = { criteria: '', formula: '', score: '' }
                  if(!ObjPerformance[val.id]) {
                     ObjPerformance[val.id] = []
                  }
                  ObjPerformance[val.id].push(Obj)
               })
               this.performance.data = ObjPerformance
            }

            this.loader_spin=false
         })
         .catch(error=> {
            this.loader_spin=false
            this.performance_type = []
            if (error.response.status === 401) {
               this.$toast.error(error.response.data.message);
               this.$router.push({ name: "Login" });
            }
         });
      },
      async addRow(id) {
         var ObjPerformance = this.performance.data
         var Obj = { criteria: '', formula: '', score: '' }

         if(!ObjPerformance[id]) {
            ObjPerformance[id] = []
         }

         ObjPerformance[id].push(Obj)
         this.performance.data = ObjPerformance
      },
      async removeRow(type_id, index) {
         this.$delete(this.performance.data[type_id], index)
      },
      async validateRange(event) {
         if(event.target.value >= 0 && event.target.value <= 100) { }
         else { event.target.value = '' }
      }
   }
}
</script>