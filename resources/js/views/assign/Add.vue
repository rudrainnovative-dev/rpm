<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="toolbar" id="kt_toolbar">
      <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
         <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
            <h1 class="d-flex align-items-center text-dark fw-bolder m-0 fs-3">Invite New Candidate</h1>
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-3">
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"Dashboard"}' class="text-link small fs-6">Dashboard</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"AssignList"}' class="text-link small fs-6">Assigned Candidates</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <p class="text-muted m-0 small fs-6">Invite New Candidate</p>
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
                    <router-link :to='{name:"AssignList"}' class="btn btn-sm btn-secondary">Back to List</router-link>
                </div>
            </div>
            <div class="col-md-12 col-12">
               <div class="card card-xl-stretch w-100 mb-xl-8 mb-5">
                  <div class="card-body">
                     <form @submit.prevent="addCandidate">
                        <div class="row">
                           <div class="col-md-4 col-12">
                              <div class="form-group">
                                 <label class="mb-2 fw-bold">Select Test</label>
                                 <select class="form-control form-control-solid form-control-sm status-filter" v-model="test_id" required>
                                    <option :value="test.id" v-for="test in tests">{{ test.name }}</option>
                                 </select>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group">
                                 <label class="mb-2 fw-bold">Email Address</label>
                                 <input type="email" class="form-control form-control-solid form-control-sm" placeholder="Enter Email Address of the Candidate" v-model="emails" v-on:blur="validationEmail($event)" required>
                                 <span class="d-inline-block text-danger mt-1" v-if="this.errors.email">{{ errors.email }}</span>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <label class="mb-2 fw-bold d-block">&nbsp;</label>
                              <button type="submit" class="btn btn-primary btn-sm d-inline-block py-3 px-6">Add</button>
                           </div>
                        </div>
                        <div class="row  align-items-end my-4">
                           <div class="col-md-12 col-12">
                              <div class="form-group checkbox-list flex-row mb-0">
                                 <label class="checkbox">
                                    <input type="checkbox" class="me-2" v-model="default_check" v-on:click="defaultCheckClick">
                                    <span></span>
                                    Set these settings as default for all the candidates
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="row" v-if="default_check">
                           <div class="col-md-12 col-12 my-4">
                              <h6 class="fw-bolder mb-5">Default Settings</h6>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group mb-0">
                                 <label for="select-test" class="mb-2 fw-bold">Start Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="default_start" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" />
                                </VueCtkDateTimePicker>
                                <span class="d-inline-block text-danger mt-1" v-if="this.errors.default_start">{{ errors.default_start }}</span>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group mb-0">
                                 <label for="select-test" class="mb-2 fw-bold">End Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="default_end" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a"/>
                                </VueCtkDateTimePicker>
                                <span class="d-inline-block text-danger mt-1" v-if="this.errors.default_end">{{ errors.default_end }}</span>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group checkbox-list mt-3">
                                 <label class="mb-2 fw-bold d-block">&nbsp;</label>
                                 <label class="checkbox">
                                    <input type="checkbox" class="me-2" v-model="default_resume">
                                    <span></span> Allow Resume
                                 </label>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>  
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 col-12">
               <div class="card card-xl-stretch w-100 mb-xl-8 mb-5" :class="required_emails.includes(index)?'border border-danger': 'border-0'" v-for="(candidate,index) in this.candidates.lists" :keys="index">
                  <div class="'alert'.index w-100 m-0 p-0 alert-dismissible fade show" :role="'alert'.index">
                     <div class="card-header border-0 d-flex justify-content-between align-items-center p-5 position-relative">
                        <h3 class="card-title fw-bolder text-dark m-0">{{ candidate.email }}</h3>
                        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light-dark bg-image-none btn-close top-50 translate-middle-y opacity-100" data-bs-dismiss="'alert'.index" aria-label="Close" style="right:1.25rem;" v-on:click="removeRow(index)">
                           <i class="fa fa-minus"></i>
                        </a>
                     </div>
                     <div class="separator mb-2"></div>
                     <div class="card-body" v-if="!default_check">
                        <h6 class="fw-bolder mb-5">Candidate Settings</h6>
                        <div class="row">
                           <div class="col-md-4 col-12">
                              <div class="form-group">
                                 <label for="select-test" class="mb-2 fw-bold">Start Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="candidate.start" :name="candidate.start" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" required />
                                </VueCtkDateTimePicker>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group">
                                 <label for="select-test" class="mb-2 fw-bold">End Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="candidate.end" :name="candidate.end" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" required />
                                </VueCtkDateTimePicker>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group checkbox-list mt-3">
                                 <label class="mb-2 fw-bold d-block">&nbsp;</label>
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
            errors: {
               email: '',
               default_end: '',
               default_start: ''
            },
            required_emails: [],
            min_datetime: moment(new Date()).format('YYYY-MM-DD hh:mm:ss'),
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
               if (error.response.status === 401) {
               this.$toast.error(error.response.data.message);
               this.$router.push({ name: "Login" });
               }
            });
         },
         async addCandidate() {

            var type = 2
            this.tests.forEach(element => {
               if(element.id == this.test_id && element.assessment_type == 'Timed Assessment') {
                  type = 1
               }
            })

            if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.emails)) {
               this.errors.email = '';
               if(!this.candidates.lists.some(field => field.email === this.emails)) {
                  this.errors.email = ''
                  var Obj = { email: this.emails, test_id: this.test_id,  start: '', end: '', type: type,resume: false}
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
               this.required_emails = []
               this.errors.default_start = ''
               this.errors.default_end = ''

               this.candidates.settings.resume = this.default_resume
               this.candidates.settings.start = this.default_start
               this.candidates.settings.end = this.default_end

               if(this.default_start == '') {
                  this.errors.default_start = 'Required Field.'
                  return false
               }
               else {
                  this.errors.default_start = ''
               }

               if(this.default_end == '') {
                  this.errors.default_end = 'Required Field.'
                  return false
               }
               else {
                  this.errors.default_end = ''
               }
            }
            else {
               
               var flags = 0
               this.required_emails = []
               this.candidates.lists.forEach((element, index) => {
                  if(element.start == '' || element.end == '') {
                     this.required_emails.push(index)
                     flags = 1
                  }
               })

               if(flags) {
                  this.$toast.error('Date input is required.')
                  return false
               }
            }

            this.disabled = true
            Assign.add(this.candidates).then(response => {
               this.$toast.success(response.data.message)
               if(response.data.actions == 'save_share') {
                  console.log('Response from the API1:', response.data)
                  this.$router.push({name:"AssignSend", params: {id:response.data.id}})
               }
               else {
                
                  this.$router.push({name:"AssignList"})  
               }
               
               this.disabled = false
            })
            .catch(error=> {
               this.disabled = false
               // this.$toast.error(error.data.message)

               if (error.response.status === 401) {
                  this.$toast.error(error.response.data.message);
                  this.$router.push({ name: "Login" });
               }
               if (error.response.status === 400) {
                  console.log('Response from the API1:', error.response.data.message)
                  this.$toast.error(error.response.data.message);
                  // this.$router.push({ name: "AssignAdd", });
               }

            });
         },
         async defaultCheckClick() {
            this.required_emails = []
         }
      },
      
   }
</script>