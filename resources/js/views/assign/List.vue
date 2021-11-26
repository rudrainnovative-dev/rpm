<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="toolbar" id="kt_toolbar">
         <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
               <h1 class="d-flex align-items-center text-dark fw-bolder m-0 fs-3">Assigned Candidates to Test</h1>
               <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-1">
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Dashboard"}' class="text-link small">Dashboard</router-link>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <p class="text-muted m-0 small">Assigned Candidates to Test</p>
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
                        <h3 class="card-title fw-bolder text-dark m-0"></h3>
                        <div class="d-flex align-items-center py-1">
                           <router-link :to='{name:"AssignAdd"}' class="btn btn-sm btn-primary me-4">Assign Candidate to Test</router-link>
                        </div>
                     </div>
                     <div class="separator mb-2"></div>
                     <div class="card-body">
                        <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                           <thead>
                               <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                   <th class="fw-bolder align-middle">Candidate</th>
                                   <th class="fw-bolder align-middle">Test Name</th>
                                   <th class="fw-bolder align-middle">Share</th>
                                   <th class="fw-bolder align-middle">Action</th>
                               </tr>
                           </thead>
                           <tbody v-if="this.assignedData.length > 0">
                              <tr v-for="assign in this.assignedData">
                                 <td class="align-middle">{{ assign.email }}</td>
                                 <td class="align-middle">{{ assign.test.name }}</td>
                                 <td class="align-middle"><span v-if="assign.share">Yes</span><span v-else>No</span></td>
                                 <td class="align-middle action-td">
                                    <ul class="list-unstyled list-inline m-0">
                                       <li class="list-inline-item mb-2" title="View" v-tooltip="tooltip.share">
                                          <router-link :to='{name:"AssignSendOne",params:{id:assign.id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button"><i class="p-0 fa fa-paper-plane"></i></button></router-link>
                                       </li>
                                       <li class="list-inline-item mb-2" title="Delete" v-tooltip="tooltip.delete">
                                          <button class="btn btn-sm btn-light-danger p-0 text-center h-30px w-30px" type="button" @click="deleteCandidate(assign.id)"><i class="p-0 fa fa-trash"></i></button>
                                       </li>
                                    </ul>
                                </td>
                               </tr>
                           </tbody>
                           <tbody v-else>
                              <tr>
                                 <td span="3">No Record Found</td>
                              </tr>
                           </tbody>
                        </table>
                        <div class="col-md-12 col-12">
                           <div class="d-flex justify-content-end align-items-center flex-wrap">
                              <pagination :data="assigned" :limit="2" @pagination-change-page="getAssigned"></pagination>
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
   import Assign from "../../apis/Assign";
   
   export default {
      name:"assign-test",
      data() {
         return {
            assigned: {},
            assignedData: [],
            loader_spin: false,
            tooltip: {
               share: 'Share to Candidates'
            }
         }
      },
      mounted() {
        this.getAssigned()
      },
      methods:{
         async getAssigned(page = 1) {
            this.loader_spin = true
            Assign.index(page).then(response => {
               this.assigned = response.data.assigned
               this.assignedData = response.data.assigned.data
               this.loader_spin = false
            })
            .catch(error=> {
                this.assigned = []
                this.loader_spin = false
            });
         },
         async deleteCandidate(id) {
            if(confirm("Are you sure to delete this candidate from test ?")){
                Assign.delete(id).then(response => {
                    this.getAssigned()
                    this.$toast.success(response.data.message);
                }).catch(error=>{
                    this.loader_spin = false 
                });
            }
        },
      }
   }
</script>