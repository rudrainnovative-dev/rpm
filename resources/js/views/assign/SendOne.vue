<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
   <div class="toolbar" id="kt_toolbar">
      <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
         <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
            <h1 class="d-flex align-items-center text-dark fw-bolder m-0 fs-3">Share with Candidate</h1>
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <router-link :to='{name:"AssignList"}' class="text-link small">Assigned Candidates to Test</router-link>
               </li>
               <li class="breadcrumb-item text-muted">
                  <p class="text-muted m-0 small">Share with Candidate</p>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="post d-flex flex-column-fluid" id="kt_post">
      <div id="kt_content_container" class="container">
         <div class="row">
            <form @submit.prevent="sendMail">
               <div class="col-md-12 col-12">
                  <div class="card-body">
                     <h5 class="fw-bolder fs-3 text-capitalize">Candidate Email:</h5>
                     <div class="alert w-100 m-0 p-0 alert-dismissible fade show" :class="'alert'.index" v-for="(candidate, index) in this.assign.lists" v-if="assign.lists.length > 0">        
                        <div class="card-header border-0 d-flex justify-content-between align-items-center p-5 position-relative">
                           <h6 class="text-normal text-dark m-0">{{ candidate.email}}</h6>
                           <!--<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light-dark bg-image-none btn-close top-50 translate-middle-y opacity-100" data-bs-dismiss="'alert'.index" aria-label="Close" style="right:1.25rem;" v-on:click="removeCandidate(index)">
                              <i class="fa fa-minus"></i>
                           </a>-->
                        </div>      
                        <div class="separator mb-2"></div>
                     </div>

                     <div class="row">
                        <div class="col-md-12 col-12 mt-5">
                           <label class="w-100 mb-2">Message<small class="text-danger">*</small> <small class="text-muted d-block my-4">(Reserve keywords: {candidate}, {test_link}.)</small></label>
                           <VueEditor v-model="assign.message" :editorToolbar="customToolbar" required/>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12 col-12 mt-5">
                           <button type="submit" class="btn btn-sm btn-primary" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Send Mail</button>
                        </div>
                     </div>
                  </div>  
               </div>
            </form>
         </div>
      </div>
   </div>
   <div class="loading" v-if="loader_spin">
      <div class="loader"></div>
   </div>
</div>
</template>

<script>
   import Assign from "../../apis/Assign";
   import { VueEditor } from "vue2-editor";

   export default {
      name:"send-test",
      components: { VueEditor },
      data() {
         return {
            assign: {
               lists: [],
               message: "",
               _method:"patch"
            },
            loader_spin: false,
            disabled: false,
            customToolbar: [
               ["bold", "italic", "underline"],
               [{ list: "ordered" }, { list: "bullet" }],
            ],
         }
      },
      mounted() {
         this.customMessage()
         this.getCandidate()
      },
      methods:{
         async getCandidate() {
            this.loader_spin = true
            Assign.showCandidate(this.$route.params.id).then(response => {
               this.assign.lists[0] = response.data.assign
               this.loader_spin = false
            })
            .catch(error=> {
               this.assign.lists = []
               this.loader_spin = false
            });
         },
         async sendMail() {
            this.disabled = true
            Assign.update(this.$route.params.id, this.assign).then(response => {
               this.$toast.success(response.data.message);
               this.$router.push({name:"AssignList"});
               this.disabled = false
            }).catch(error=>{
               this.disabled = false
            })
         },
         async removeCandidate(index) {
            this.$delete(this.assign.lists, index)
         },
         async customMessage() {
            this.assign.message = '<h4 class="my-4">Dear {candidate}</h4><p class="my-4">Please find attached online test link as part of our interview process.</p><p class="my-8 test-center"><a href="{test_link}" target="_blank"><strong>Online Test Link</strong></a></p><p class="my-4">Keep in mind that there are no right answers.</p><p class="my-4">This test is designed is to gauge your skills and give us an idea of how you approach tasks relevant to the PHP role.</p><p class="my-4">Please do not hesitate to get in touch if you have any questions.</p><p class="my-8">Best of luck with the Online Test</p><p>Thank You</p><p>Rudra Innovative.</p>'
         }
      }
   }
</script>