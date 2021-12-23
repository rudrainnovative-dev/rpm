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
                  <form @submit.prevent="addCandidate">
                     <div class="row">
                        <div class="col-md-4 col-12">
                           <div class="form-group">
                              <label class="mb-2 fw-bold">Select Test</label>
                              <select class="form-control form-control-solid form-control-sm" v-model="test_id" required>
                                 <option :value="test.id" v-for="test in tests">{{ test.name }}</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <div class="form-group">
                              <label class="mb-2 fw-bold">Email Address</label>
                              <input type="email" class="form-control form-control-solid form-control-sm" placeholder="Enter Email Address of the Candidate" v-model="emails" v-on:blur="validationEmail($event)" required>
                              <span class="d-block text-danger" v-if="this.errors.email">{{ errors.email }}</span>
                           </div>
                        </div>
                        <div class="col-md-4 col-12">
                           <button type="submit" class="btn btn-primary btn-sm d-inline-block mt-4">Add</button>
                        </div>
                     </div>
                     <div class="row  align-items-end my-4">
                        <div class="col-md-12 col-12">
                           <div class="form-group checkbox-list flex-row justify-content-md-end">
                              <label class="checkbox">
                                 <input type="checkbox" class="me-2" v-model="default_check">
                                 <span></span>
                                 Set these settings as default for all the candidates
                              </label>
                           </div>
                        </div>
                     </div>
                     <div class="row" v-if="default_check">
                        <div class="col-md-12 col-12">
                           <div class="card card-xl-stretch w-100 mb-xl-8 mb-5">
                              <div class="card-body p-0">
                                 <h6 class="fw-bolder mb-5">Default Settings</h6>
                                 <div class="row">
                                    <div class="col-md-4 col-12">
                                       <div class="form-group">
                                          <label for="select-test" class="mb-2 fw-bold">Start Time & Date</label>
                                          <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="default_start" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                             <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" required />
                                         </VueCtkDateTimePicker>
                                         <span class="d-block text-danger" v-if="this.errors.default_start">{{ errors.default_start }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                       <div class="form-group">
                                          <label for="select-test" class="mb-2 fw-bold">End Time & Date</label>
                                          <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="default_end" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                             <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" required/>
                                         </VueCtkDateTimePicker>
                                         <span class="d-block text-danger" v-if="this.errors.default_end">{{ errors.default_end }}</span>
                                       </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                       <div class="form-group checkbox-list mt-3">
                                          <label class="checkbox">
                                             <input type="checkbox" class="me-2" v-model="default_resume">
                                             <span></span> Allow Resume
                                          </label>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>  
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 col-12">
               <div class="card card-xl-stretch w-100 mb-xl-8 mb-5" v-for="(candidate,index) in this.candidates.lists">
                  <div class="'alert'.index w-100 m-0 p-0 alert-dismissible fade show" :role="'alert'.index">
                     <div class="card-header border-0 d-flex justify-content-between align-items-center p-5 position-relative">
                        <h3 class="card-title fw-bolder text-dark m-0">{{ candidate.email }}</h3>
                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light-dark bg-image-none btn-close top-50 translate-middle-y opacity-100" data-bs-dismiss="'alert'.index" aria-label="Close" style="right:1.25rem;" v-on:click="removeRow(index)">
                           <i class="fa fa-minus"></i>
                        </a>
                     </div>
                     <div class="separator mb-2"></div>
                     <div class="card-body">
                        <h6 class="fw-bolder mb-5">Candidate Settings</h6>
                        <div class="row">
                           <div class="col-md-4 col-12">
                              <div class="form-group">
                                 <label for="select-test" class="mb-2 fw-bold">Start Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="candidate.start" :name="candidate.start" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a"  required />
                                </VueCtkDateTimePicker>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group">
                                 <label for="select-test" class="mb-2 fw-bold">End Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="candidate.end" :name="candidate.end" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a"  required />
                                </VueCtkDateTimePicker>
                              </div>
                           </div>
                           <div class="col-md-4 col-12"></div>
                           <div class="col-md-12 col-12">
                              <div class="form-group checkbox-list mt-3">
                                 <label class="checkbox">
                                    <input type="checkbox" class="me-2" v-model="candidate.resume" :name="candidate.resume">
                                    <span></span> Allow Resume
                                 </label>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>                           
               </div>
            </div>
            <div class="col-md-12 col-12 text-md-end" v-if="this.candidates.lists.length > 0">
               <ul class="list-unstyled list-inline m-0">
                  <li class="list-inline-item">
                     <button type="button" class="btn btn-dark btn-sm" v-on:click="create('save_share')" :disabled="disabled">Save & Share</button>
                  </li>
                  <li class="list-inline-item">
                     <button type="button" class="btn btn-secondary btn-sm" v-on:click="create('save')" :disabled="disabled">Save & Share Later</button>
                  </li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>
</template>

<script>
   import Assign from "../../apis/Assign";
   import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
   import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
   import moment from 'moment'

   export default {
      name:"assign-test",
      components: { VueCtkDateTimePicker },
      data() {
         return {
            tests:{},
            test_id: '',
            emails: '',
            default_check: '',
            default_start: '',
            default_end: '',
            default_resume: false,            
            candidates: {
               lists: [],
               settings: {
                  resume: false,
                  start: '',
                  end: ''
               },
               actions: '',
               default_check: false
            },
            min_datetime: '',
            errors: {
               email: '',
               default_end: '',
               default_start: ''
            },
            loader_spin: true,
            disabled: false,
         }
      },
      mounted() {
         this.getTests()
         this.getCurrentDate()
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
   
            if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.emails)) {
               this.errors.email = '';
               if(!this.candidates.lists.some(field => field.email === this.emails)) {
                  this.errors.email = ''
                  var Obj = { email: this.emails, test_id: this.test_id,  start: '', end: '', resume: false}
                  this.candidates.lists.push(Obj) 
                  this.emails = ''
               }
               else {
                  this.errors.email = 'Email already added.'
               }
            }
            else {
               this.errors.email = 'Invalid Email Address.';
            }
         },
         async removeRow(index) {
            this.$delete(this.candidates.lists, index)
         },
         async getCurrentDate() {
            this.min_datetime = moment(new Date()).format('YYYY-MM-DD hh:mm:ss')
         },
         async validationEmail(event) {
            if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(event.target.value)) {
               this.errors.email = '';
            }
            else {
               this.errors.email = 'Invalid Email Address.';
            }
         },
         async create(action) {
            this.candidates.actions = action
            this.candidates.default_check = this.default_check
            if(this.default_check) {
               this.candidates.settings.resume = this.default_resume
               this.candidates.settings.start = this.default_start
               this.candidates.settings.end = this.default_end
               if(this.default_start == '') {
                  this.errors.default_start = 'Required.'
                  return false
               }
               else {
                  this.errors.default_start = ''
               }

               if(this.default_end == '') {
                  this.errors.default_end = 'Required.'
                  return false
               }
               else {
                  this.errors.default_end = ''
               }
            }
            this.disabled = true
            Assign.add(this.candidates).then(response => {
               this.$toast.success(response.data.message)
               if(response.data.actions == 'save_share') {
                  this.$router.push({name:"AssignSend", params: {id:response.data.id}})
               }
               else {
                  this.$router.push({name:"AssignList"})  
               }
               this.disabled = false
            })
            .catch(error=> {
               this.disabled = false
               this.$toast.error(error.data.message)
            });
         }
      },
      
   }
</script>