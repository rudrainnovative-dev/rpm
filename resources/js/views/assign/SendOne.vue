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
                     <div class="card card-xl-stretch w-100 mb-xl-8 mb-5">
                        <div class="card-body">
                           <h5 class="fw-bolder fs-3 text-capitalize">Candidate Email:</h5>
                           <div class="alert w-100 m-0 p-0 alert-dismissible fade show" :class="'alert'.index" v-for="(candidate, index) in this.assign.lists" v-if="assign.lists.length > 0">        
                              <div class="card-header border-0 d-flex justify-content-between align-items-center py-5 px-0 position-relative">
                                 <h6 class="text-normal text-dark m-0">{{ candidate.email}}</h6>
                           <!--<a href="javascript:void(0);" class="btn btn-icon btn-sm btn-light-dark bg-image-none btn-close top-50 translate-middle-y opacity-100" data-bs-dismiss="'alert'.index" aria-label="Close" style="right:1.25rem;" v-on:click="removeCandidate(index)">
                              <i class="fa fa-minus"></i>
                           </a>-->
                        </div>      

                     </div>

                     <div class="row">
                        <div class="col-md-12 col-12 mt-5">
                           <label class="w-100 mb-2">Message<small class="text-danger">*</small> <small class="text-muted d-block my-4">(Reserve keywords: {candidate}, {test_link}, {job_role}, {assign_from}, {assign_to}.)</small></label>
                           <wysiwyg v-model="assign.message" required/>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12 col-12 mt-5">
                           <button type="submit" class="btn btn-sm btn-primary" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Send Mail</button>
                        </div>
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

   export default {
      name:"send-test",
      data() {
         return {
            assign: {
               lists: [],
               message: "",
               _method:"patch"
            },
            loader_spin: false,
            disabled: false,
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
               if (error.response.status === 401) {
                  this.$toast.error(error.response.data.message);
                  this.$router.push({ name: "Login" });
               }
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
               if (error.response.status === 401) {
                  this.$toast.error(error.response.data.message);
                  this.$router.push({ name: "Login" });
               }
            })
         },
         async removeCandidate(index) {
            this.$delete(this.assign.lists, index)
         },
         async customMessage() {
            this.assign.message = '<table border="0" cellpadding="10" cellspacing="0" style="background-color: #f5f8fa; width: 600px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; border-radius: 7px;"> <tr> <td> <table border="0" cellpadding="10" cellspacing="0" width="100%"> <tbody> <tr> <td> <p style="font-size:15px; color:#333; font-family: Arial, Helvetica, sans-serif;">Dear <b style="text-transform: uppercase;">{candidate}</b>,</p> <p style="font-size: 15px; margin: 15px 0 0 0; line-height: 24px; color:#333; font-family: Arial, Helvetica, sans-serif;"> You have been invited to take the assessment from: <b style="text-transform: uppercase;">{assign_from}</b> to: <b style="text-transform: uppercase;">{assign_to}</b>.</p> <p style="font-size: 15px; margin: 15px 0 0 0; line-height: 24px; color:#333; font-family: Arial, Helvetica, sans-serif;"> Please find attached online test link as part of our interview process. You can start reviewing the Online Test by clicking the following button:</p> </td> </tr> <tr> <td><a href="{test_link}" style="background-color:#009ef7;border:1px solid #009ef7;border-radius:8px;color:#ffffff;display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size:15px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Start Your Test</a></td> </tr> <tr> <td> <h2 style="display: inline-block;"> Or gothrough this link:- </h2> <a style="color: #009ef7;">{test_link}</a> </td> </tr> <tr> <td> <p style="line-height: 23px; font-size: 15px; margin: 15px 0 15px 0; color:#333;font-family: Arial, Helvetica, sans-serif;"> Keep in mind that there are no right answers. This test is designed is to gauge your skills and give us an idea of how you approach tasks relevant to the <b style="text-transform: uppercase;">{job_role}</b>.</p> <p style="line-height: 23px; font-size: 15px; color:#333;font-family: Arial, Helvetica, sans-serif;"> Please do not hesitate to get in touch if you have any questions.</p> <p style="line-height: 23px; font-size: 15px; color:#333;font-family: Arial, Helvetica, sans-serif;"> Best of luck with the Online Test.</p> <p style="margin: 30px 0 0; font-size: 15px; color:#333;font-family: Arial, Helvetica, sans-serif;"> Regards,</p> <p style="margin:4px 0 0 0; font-size: 15px; color:#333;font-family: Arial, Helvetica, sans-serif;"> Rudra Innovative</p> </td> </tr> <tr> <th> <div style="width:100%; height: 1px; margin:0 0 0 0; border-top:1px solid #e9e9e9; font-size: 1px; color:transparent;"> 1</div> </th> </tr> </tbody> </table> </td> </tr> </table>'
         }
      }
   }
</script>