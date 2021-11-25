<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="toolbar" id="kt_toolbar">
         <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title  me-3 mb-5 mb-lg-0 lh-1">
               <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Set Performance Criteria</h1>
               <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Performance"}' class="text-muted small">Performance Criteria</router-link>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <p class="text-muted m-0 small">Set Performance Criteria</p>
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
                        <form @submit.prevent="update">
                           <div class="row">
                              <div class="col-md-4 col-12">
                                 <div class="form-group">
                                    <label for="select-test" class="mb-2 fw-bold">Valid From<small class="text-danger">*</small></label>
                                    <input type="date" class="form-control form-control-solid form-control-sm" v-model="performance.from" required>
                                 </div>
                              </div>
                              <div class="col-md-4 col-12">
                                 <div class="form-group">
                                    <label for="select-test" class="mb-2 fw-bold">Valid To<small class="text-danger">*</small></label>
                                    <input type="date" class="form-control form-control-solid form-control-sm" v-model="performance.to" required>
                                 </div>
                              </div>
                           </div>
                           <div class="row mb-3" v-for="type in performance_type">
                              <div class="col-md-12 col-12 py-3">
                                 <h5 class="fw-bolder fs-3 text-capitalize">{{ type.name }}</h5>
                              </div>
                              <div class="col-md-4 col-12">
                                 <div class="form-group">
                                    <label class="w-100 mb-2">Criteria Type<small class="text-danger">*</small></label>
                                    <select class="form-control form-control-solid form-control-sm" v-model="performance.criteria[type.id]" required>
                                      <option :value="'Section'">Section</option>
                                      <option :value="'Overall'">Overall</option>
                                    </select>
                                 </div>
                              </div>

                              <div class="col-md-4 col-12">
                                 <div class="form-group">
                                    <label class="w-100 mb-2">Formula Value<small class="text-danger">*</small></label>
                                    <select class="form-control form-control-solid form-control-sm" v-model="performance.formula[type.id]" required>
                                       <option :value="'>'">Greater Than</option>
                                       <option :value="'='">Equal Than</option>
                                       <option :value="'<'">Less Than</option>
                                    </select>
                                </div>
                              </div>

                              <div class="col-md-4 col-12">
                                 <div class="form-group">
                                    <label class="w-100 mb-2">Score Percentage<small class="text-danger">*</small></label>
                                    <input type="number" class="form-control form-control-solid form-control-sm" v-model="performance.score[type.id]" placeholder="Enter a value" min = '0' required>
                                 </div>
                              </div>

                              <div class="col-md-12 col-12 mt-5">
                                 <label class="w-100 mb-2">Remarks</label>
                                 <textarea class="form-control form-control-solid form-control-sm" rows="3" v-model="performance.remarks[type.id]" placeholder="Start typing here"></textarea>
                              </div>
                           </div>

                           <div class="col-md-12 col-12 text-md-end">
                              <ul class="list-unstyled list-inline m-0">
                                 <li class="list-inline-item">
                                    <button type="submit" class="btn btn-dark btn-sm">Update</button>
                                 </li>
                                 <li class="list-inline-item">
                                    <router-link :to='{name:"Performance"}' class="btn btn-light btn-sm">Cancel</router-link>
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
            performance:{
               from: '',
               to: '',
               criteria: [],
               formula: [],
               score: [],
               remarks: [],
               _method:"patch",
            },
            performance_type:[],
            disabled: false,
            loader_spin: true
        }
    },
    mounted() {
      this.getPerformanceType()
      this.getPerformance()
    },
    methods:{
         async getPerformanceType() {
            Performance.type().then(response => {
               this.performance_type = response.data.performance_type;
            })
            .catch(error=> {
               this.performance_type = []
            });
         },
         async getPerformance() {
            Performance.show(this.$route.params.id).then(response => {
               const { from, to } = response.data.performance
               this.performance.from = from
               this.performance.to = to
             
               if(response.data.options) {
                  var k = 1
                  for(var i = 0; i < response.data.options.length; i++) {
                     var options = response.data.options[i]
                     this.performance.criteria[k] = options.criteria
                     this.performance.formula[k] = options.formula
                     this.performance.score[k] = options.score
                     this.performance.remarks[k] = options.remarks
                     k++
                  }
               }

               this.loader_spin = false
            }).catch(error=> {
                this.loader_spin = false
            })
         },
         async update(){
            this.disabled = true
            Performance.update(this.$route.params.id, this.performance).then(response => {
                this.$toast.success(response.data.message);
                this.$router.push({name:"Performance"});
                this.disabled = false
            }).catch(error=>{
                this.disabled = false
            })
         },
    }
}
</script>