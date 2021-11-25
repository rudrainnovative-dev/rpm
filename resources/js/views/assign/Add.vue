<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="toolbar" id="kt_toolbar">
      <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
         <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
            <h1 class="d-flex align-items-center text-dark fw-bolder m-0 fs-3">Assign Candidate to Test</h1>
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <p class="text-muted m-0 small">Assign Candidate to Test</p>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="post d-flex flex-column-fluid" id="kt_post">
      <div id="kt_content_container" class="container">
         <div class="row">
            <div class="col-md-12 col-12">
               <div class="card-body">
                  <div class="row align-items-end">
                     <div class="col-md-3 col-12">
                        <div class="form-group">
                           <label class="mb-2 fw-bold">Select Test</label>
                           <select class="form-control form-control-solid form-control-sm" v-model="test_id">
                              <option :value="test.id" v-for="test in tests">{{ test.name }}</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-3 col-12">
                        <div class="form-group">
                           <label class="mb-2 fw-bold">Email Address</label>
                           <input type="email" class="form-control form-control-solid form-control-sm" placeholder="Enter Email Address of the Candidate" v-model="emails">
                        </div>
                     </div>
                     <div class="col-md-1 col-12">
                        <a href="javascript:void(0);" type="button" class="btn btn-primary btn-sm d-inline-block mb-4" v-on:click="addCandidate">Add</a>
                     </div>
                     <div class="col-md-5 col-12"></div>
                  </div>
               </div>  
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 col-12">
               <div class="form-group checkbox-list flex-row justify-content-md-end mb-4">
                  <label class="checkbox">
                     <input type="checkbox" class="me-2">
                     <span></span>
                     Set these settings as default for all the candidates
                  </label>
               </div>
            </div>
         </div>
         
         <div v-for="(val, key) in candidates" :keys="key">
         {{ key }}
         </div>

      </div>
   </div>
</div>
</template>

<script>
   import Assign from "../../apis/Assign";

   export default {
      name:"assign-test",
      data() {
         return {
            tests:{},
            test_id: '',
            emails: '',
            candidates: {},  
            loader_spin: true,
            disabled: false,
         }
      },
      mounted() {
         this.getTests()
      },
      methods:{
         async getTests() {
            Assign.getAllTest().then(response => {
               this.tests = response.data.tests
               this.loader_spin = false
            })
            .catch(error=> {
               this.tests = []
               this.loader_spin = false
            });
         },
         async addCandidate() {

            if(this.test_id && this.emails) {
               var ObjAssigns = this.candidates
               var Obj = { test_id: this.test_id, start: '', end: '', resume: '' }
               
               if(!ObjAssigns[this.emails]) {
                  ObjAssigns[this.emails] = []
               }
               
               ObjAssigns[this.emails].push(Obj)
               this.candidates = ObjAssigns
               console.log(this.candidates)
            }
         }
      }
   }
</script>