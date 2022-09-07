<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="toolbar" id="kt_toolbar">
      <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
         <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
            <h1 class="d-flex align-items-center text-dark fw-bolder m-0 fs-3">Update Candidate Details</h1>
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-3">
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"Dashboard"}' class="text-link small fs-6">Dashboard</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"AssignList"}' class="text-link small fs-6">Assigned Candidates to Test</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <p class="text-muted m-0 small fs-6">Update Candidate Details</p>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="post d-flex flex-column-fluid" id="kt_post">
      <div id="kt_content_container" class="container">
         <div class="row">
            <div class="col-md-12 col-12">
               <div class="card card-xl-stretch w-100 mb-xl-8 mb-5">
                  <div class="card-body">
                     <form>
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
                                 <span class="d-inline-block text-danger mt-1" v-if="this.errors.email">{{ errors.email }}</span>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-4 col-12">
                              <div class="form-group mb-0">
                                 <label for="select-test" class="mb-2 fw-bold">Start Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="start_time" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" :value="start_time" />
                                </VueCtkDateTimePicker>
                                <span class="d-inline-block text-danger mt-1" v-if="this.errors.start_time">{{ errors.start_time }}</span>
                              </div>
                           </div>
                           <div class="col-md-4 col-12">
                              <div class="form-group mb-0">
                                 <label for="select-test" class="mb-2 fw-bold">End Time & Date</label>
                                 <VueCtkDateTimePicker :no-value-to-custom-elem="false" v-model="end_time" format="YYYY-MM-DD hh:mm a" formatted = "YYYY-MM-DD hh:mm a" :min-date="min_datetime">
                                    <input type="text" class="form-control form-control-solid form-control-sm" placeholder="YYYY-MM-DD hh:mm a" :value="end_time"/>
                                </VueCtkDateTimePicker>
                                <span class="d-inline-block text-danger mt-1" v-if="this.errors.end_time">{{ errors.end_time }}</span>
                              </div>
                           </div>

                           <div class="col-md-4 col-12">
                              <div class="form-group checkbox-list mt-3">
                                 <label class="mb-2 fw-bold d-block">&nbsp;</label>
                                 <label class="checkbox">
                                    <input type="checkbox" class="me-2" v-model="is_resume">
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
         
            <div class="col-md-12 col-12 text-md-end">
               <ul class="list-unstyled list-inline m-0">
                  <li class="list-inline-item">
                     <button type="button" class="btn btn-dark btn-sm" v-on:click="update('save')" :disabled="disabled">Save</button>
                  </li>
                  <li class="list-inline-item">
                     <button type="button" class="btn btn-secondary btn-sm" v-on:click="update('save_share')" :disabled="disabled">Save & Share</button>
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
            start_time: '',
            end_time: '',
            is_resume: false, 
            candidate_detail:{
               email: '',
               end: '',
               test_id: '',
               name: '',
               resume: '',  
            },
            errors: {
               email: '',
               end_time: '',
               start_time: ''
            },
            required_emails: [],
            min_datetime: moment(new Date()).format('YYYY-MM-DD hh:mm:ss'),
            loader_spin: true,
            disabled: false,
         }
      },
      mounted() {
         this.getTests()
         this.getCandidateDetails()
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
         async getCandidateDetails() {
            Assign.showCandidate(this.$route.params.id).then(response => {
               this.emails = response.data.assign.email
               this.test_id = response.data.assign.test.id
               this.start_time = moment(response.data.assign.start).format('YYYY-MM-DD hh:mm A')
               this.end_time =  moment(response.data.assign.end).format('YYYY-MM-DD hh:mm A')
               this.is_resume = response.data.assign.resume
               console.log(response.data.assign)
            })
            .catch(error=> {
               this.tests = []
               this.loader_spin = false
            });
         }, 

         async validationEmail(event) {
            if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(event.target.value)) {
               this.errors.email = '';
            }
            else {
               this.errors.email = 'Invalid Email Address.';
            }
         },

         async update(action) { 
            this.disabled = true;
            this.candidate_detail.email = this.emails
            this.candidate_detail.start = moment(this.start_time).format('YYYY-MM-DD HH:mm:ss')
            this.candidate_detail.end = moment(this.end_time).format('YYYY-MM-DD HH:mm:ss')
            this.candidate_detail.test_id = this.test_id
            this.candidate_detail.name = this.emails.split('@')[0]
            this.candidate_detail.resume = this.is_resume
            console.log(this.candidate_detail.end);
            Assign.update(this.$route.params.id, {candidate: this.candidate_detail, id: this.$route.params.id, for: "update_assigned_candidate"}).then(response => {
               this.$toast.success(response.data.message)
               if(action == 'save_share') {
                  this.$router.push({name:"AssignSendOne", params: {id: this.$route.params.id}})
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
         },

         async defaultCheckClick() {
            this.required_emails = []
         }
      },
      
   }
</script>