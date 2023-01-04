<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="toolbar" id="kt_toolbar">
         <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-place="true" data-kt-place-mode="prepend"
               data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
               class="page-title me-3 mb-5 mb-lg-0 lh-1">
               <h1 class="d-flex align-items-center text-dark fw-bolder m-0 fs-3">Assigned Candidates to Test</h1>
               <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 mt-3">
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Dashboard"}' class="fs-6 text-link small">Dashboard</router-link>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <p class="text-muted m-0 fs-6">Assigned Candidates to Test</p>
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
                        <div class="form-group my-3">
                           <div class="input-group">
                              <input type="text" placeholder="Search Candidate..."
                                 class="form-control form-control-sm form-control-solid bg-white" v-model="search"
                                 v-on:keyup.enter="searchClick">
                              <div class="input-group-append">
                                 <button type="button" class="btn btn-light-dark btn-sm px-3 rounded-0 rounded-end"
                                    v-on:click="searchClick">
                                    <span class="svg-icon svg-icon-2 m-0">
                                       <svg xmlns="http://www.w3.org/2000/svg"
                                          xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                          viewBox="0 0 24 24" version="1.1">
                                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                             <rect x="0" y="0" width="24" height="24"></rect>
                                             <path
                                                d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                             <path
                                                d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                fill="#000000" fill-rule="nonzero"></path>
                                          </g>
                                       </svg>
                                    </span>
                                 </button>
                              </div>
                           </div>
                        </div>
                        <div class="form-group my-3">
                           <div class="input-group">
                              <select v-model="filter" @change="filterClick"
                                 class="form-control form-control-sm form-control-solid bg-white">
                                 <option value="" selected>Select To filter by status</option>
                                 <option value="0">Pending</option>
                                 <option value="1">Completed</option>
                                 <option value="-1">In Progress</option>
                              </select>
                           </div>
                        </div>
                        <div class="d-flex align-items-center py-1">
                           <router-link :to='{name:"AssignAdd"}' class="btn btn-sm btn-primary">Assign Candidate to Test
                           </router-link>
                        </div>
                     </div>
                     <div class="separator mb-2"></div>
                     <div class="card-body">
                        <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                           <thead>
                              <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                 <th class="fw-bolder align-middle">Candidate</th>
                                 <th class="fw-bolder align-middle">Test Name</th>
                                 <th class="fw-bolder align-middle">Status</th>
                                 <th class="fw-bolder align-middle text-center">Share</th>
                                 <th class="fw-bolder align-middle text-center">Action</th>
                              </tr>
                           </thead>
                           <tbody v-if="this.assignedData.length > 0">
                              <tr v-for="assign in this.assignedData">
                                 <td class="align-middle">
                                    <span> {{ assign.email }}

                                       <svg v-if="assign.status != 1 && assign.status != -1 && assign.share" width="24px" height="20px" viewBox="0 0 24 24"
                                          version="1.1" xmlns="http://www.w3.org/2000/svg"
                                          xmlns:xlink="http://www.w3.org/1999/xlink" v-tooltip="tooltip.copy_test_link"
                                          class="shadow-none" style="outline: none !important;" role="button"
                                          @click="copyToClipBoard(assign.test.public_id, assign.id)">
                                          <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none"
                                             fill-rule="evenodd">
                                             <g id="ic_fluent_copy_24_regular" fill="#3490dc" fill-rule="nonzero">
                                                <path
                                                   d="M5.50280381,4.62704038 L5.5,6.75 L5.5,17.2542087 C5.5,19.0491342 6.95507456,20.5042087 8.75,20.5042087 L17.3662868,20.5044622 C17.057338,21.3782241 16.2239751,22.0042087 15.2444057,22.0042087 L8.75,22.0042087 C6.12664744,22.0042087 4,19.8775613 4,17.2542087 L4,6.75 C4,5.76928848 4.62744523,4.93512464 5.50280381,4.62704038 Z M17.75,2 C18.9926407,2 20,3.00735931 20,4.25 L20,17.25 C20,18.4926407 18.9926407,19.5 17.75,19.5 L8.75,19.5 C7.50735931,19.5 6.5,18.4926407 6.5,17.25 L6.5,4.25 C6.5,3.00735931 7.50735931,2 8.75,2 L17.75,2 Z M17.75,3.5 L8.75,3.5 C8.33578644,3.5 8,3.83578644 8,4.25 L8,17.25 C8,17.6642136 8.33578644,18 8.75,18 L17.75,18 C18.1642136,18 18.5,17.6642136 18.5,17.25 L18.5,4.25 C18.5,3.83578644 18.1642136,3.5 17.75,3.5 Z"
                                                   id="🎨-Color"></path>
                                             </g>
                                          </g>
                                       </svg>
                                    </span>
                                    <small class="d-block text-muted">{{ assign.start | formatFullDate }} - {{
                                       assign.end | formatFullDate }}</small>
                                 </td>
                                 <td class="align-middle">{{ assign.test.name }}</td>
                                 <td class="align-middle"><span class="badge badge-warning"
                                       v-if="assign.status == -1">In Progress</span> <span class="badge badge-success"
                                       v-else-if="assign.status == 1">Completed</span> <span class="badge badge-danger"
                                       v-else>Pending</span></td>
                                 <td class="align-middle text-center"><span v-if="assign.share">Yes</span><span
                                       v-else>No</span></td>
                                 <td class="text-center">
                                    <ul class="list-unstyled list-inline m-0">
                                       <li class="list-inline-item mb-2" title="View" v-tooltip="tooltip.share">
                                          <router-link :to='{name:"AssignSendOne",params:{id:assign.id}}'><button
                                                class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px"
                                                type="button"><i class="p-0 fa fa-paper-plane"></i></button>
                                          </router-link>
                                       </li>
                                       <li class="list-inline-item mb-2" title="View Report" v-tooltip="tooltip.report"
                                       v-if="(assign.test_taker)">
                                       <router-link :to='{name:"ReportShow",params:{id:assign.test_taker.id}}' target="_blank"><button
                                             class="btn btn-sm btn-light-info p-0 text-center h-30px w-30px"
                                             type="button"><i class="p-0 fa fa-eye"></i></button></router-link>
                                       </li>
                                       <li class="list-inline-item mb-2" title="Edit" v-tooltip="tooltip.edit"
                                          v-if="(assign.status == 0 || assign.status == -1)">
                                          <router-link :to='{name:"AssignEditOne",params:{id:assign.id}}'><button
                                                class="btn btn-sm btn-light-warning p-0 text-center h-30px w-30px"
                                                type="button"><i class="p-0 fa fa-edit"></i></button></router-link>
                                       </li>
                                       <li class="list-inline-item mb-2" title="Delete" v-tooltip="tooltip.delete"
                                          v-if="assign.status == 0">
                                          <button class="btn btn-sm btn-light-danger p-0 text-center h-30px w-30px"
                                             type="button" @click="deleteCandidate(assign.id)"><i
                                                class="p-0 fa fa-trash"></i></button>
                                       </li>

                                    </ul>
                                 </td>
                              </tr>
                           </tbody>
                           <tbody v-else>
                              <tr>
                                 <td colspan="5" class="text-center text-danger"> No Record Found</td>
                              </tr>
                           </tbody>
                        </table>
                        <div class="col-md-12 col-12 mt-2">
                           <div class="d-flex justify-content-end align-items-center flex-wrap">
                              <pagination :data="assigned" :limit="2" @pagination-change-page="getAssigned">
                              </pagination>
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
      name: "assign-test",
      data() {
         return {
            assigned: {},
            assignedData: [],
            loader_spin: false,
            tooltip: {
               share: 'Share to Candidates',
               edit: 'Edit details',
               copy_test_link: 'Copy test link',
               report: 'View test report'
            },
            search: '',
            filter: '',
         }
      },
      mounted() {
         this.getAssigned()
      },
      methods: {
         async getAssigned(page = 1) {
            this.loader_spin = true
            Assign.index(page, this.search, this.filter).then(response => {
               this.assigned = response.data.assigned
               this.assignedData = response.data.assigned.data
               this.loader_spin = false

            })
               .catch(error => {
                  this.assigned = []
                  this.loader_spin = false
               });
         },
         async deleteCandidate(id) {
            if (confirm("Are you sure to delete this candidate from test ?")) {
               Assign.delete(id).then(response => {
                  this.getAssigned()
                  this.$toast.success(response.data.message);
               }).catch(error => {
                  this.loader_spin = false
               });
            }
         },
         async searchClick() {
            this.getAssigned()
         },
         async filterClick() {
            this.getAssigned()
         },
         copyToClipBoard(public_id, assign_id) {
            let textToCopy = window.location.origin + '/online-test/' + public_id + '/' + btoa(assign_id);
            const tmpTextField = document.createElement("textarea")
            tmpTextField.textContent = textToCopy
            tmpTextField.setAttribute("style", "position:absolute; right:200%;")
            document.body.appendChild(tmpTextField)
            tmpTextField.select()
            tmpTextField.setSelectionRange(0, 99999) /*For mobile devices*/
            document.execCommand("copy")
            tmpTextField.remove()
            this.$toast.success("Test Url copied");
         },
      }
   }
</script>