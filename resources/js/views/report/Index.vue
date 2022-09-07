<template>
   <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="toolbar" id="kt_toolbar">
         <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div
               data-kt-place="true"
               data-kt-place-mode="prepend"
               data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
               class="page-title me-3 mb-5 mb-lg-0 lh-1"
               >
               <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">
                  Test Performance
               </h1>
               <ul
                  class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3"
                  >
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Dashboard"}' class="text-link small fs-6"
                        >Dashboard</router-link
                        >
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <router-link :to='{name:"Test"}' class="text-link small fs-6"
                        >Test</router-link
                        >
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <p class="text-muted m-0 small fs-6">Test Performance</p>
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
                        <div class="card-toolbar w-100 mb-4" :class="(tab3)?'invisible':'visible'">
                           <div class="text-end w-100">
                             <a href="javascript:void(0)" class="btn btn-primary btn-sm" v-on:click="sendReportClick">Send Report to Candidate</a>

                           </div>
                        </div>
                        <div class="card-toolbar m-0">
                           <ul class="nav nav-tabs nav-tabs-line rounded border-transparent" style="margin-bottom: -1px;">
                              <li class="nav-item">
                                 <a
                                    :class="tab1?'nav-link active fs-6 fw-bold cursor-pointer':'nav-link fs-6 fw-bold cursor-pointer'"
                                    v-on:click="tabsClick('tab1')"
                                    >Completed</a
                                    >
                              </li>
                              <li class="nav-item">
                                 <a
                                    :class="tab2?'nav-link active fs-6 fw-bold cursor-pointer':'nav-link fs-6 fw-bold cursor-pointer'"
                                    v-on:click="tabsClick('tab2')"
                                    >In Progress</a
                                    >
                              </li>
                              <li class="nav-item">
                                 <a
                                    :class="tab3?'nav-link active fs-6 fw-bold cursor-pointer':'nav-link fs-6 fw-bold cursor-pointer'"
                                    v-on:click="tabsClick('tab3')"
                                    >Upcoming</a
                                    >
                              </li>
                           </ul>
                        </div>
                        <div class="tab-content border p-4 rounded-0 rounded-bottom rounded-end" id="myTabContent">
                           <div class="tab-pane fade active show" v-if="tab1">
                              <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                                 <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                       <th class="fw-bolder align-middle">#</th>
                                       <th class="fw-bolder align-middle">Name</th>
                                       <th class="fw-bolder align-middle text-center">Test Date</th>
                                       <th class="fw-bolder align-middle text-center">Score(%)</th>
                                       <th class="fw-bolder align-middle text-center">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody
                                    v-if="reports.data && Object.keys(reports.data).length > 0"
                                    >
                                    <tr v-for="(report, key) in reports.data" :key="key">
                                       <td class="align-middle">
                                          <div class="form-group checkbox-list flex-row m-0">
                                             <label class="checkbox">
                                             <input
                                                type="checkbox"
                                                class="me-2"
                                                v-model="selected_report"
                                                :value="report.id"
                                                />
                                             <span></span>
                                             </label>
                                          </div>
                                       </td>
                                       <td class="align-middle">
                                          <span class="d-block">{{ report.name }}</span>
                                          <span
                                             class="d-block text-muted"
                                             >{{ report.email }}</span
                                             >
                                       </td>
                                       <td class="align-middle text-center">{{ report.created_at | formatDate }}</td>
                                       <td class="align-middle text-center">
                                          {{ Math.ceil(report.correct_marks * 100 / report.total_marks) }}
                                       </td>
                                       <td class="align-middle text-center">
                                          <ul class="list-unstyled list-inline m-0">
                                             <li class="list-inline-item mb-2" v-tooltip="'Download Report'">
                                                <button class=" btn btn-sm btn-light-dark p-0 text-center h-30px w-30px "
                                                   type="button" @click="downloadReport(report.id)"><i class="p-0 fa fa-download"></i></button>
                                             </li>
                                             <li
                                                class="list-inline-item mb-2"
                                                title="View"
                                                v-tooltip="'View Report'"
                                                >
                                                <router-link
                                                   :to='{name:"ReportShow",params:{id:report.id}}'
                                                   ><button
                                                   class="
                                                   btn btn-sm btn-light-dark
                                                   p-0
                                                   text-center
                                                   h-30px
                                                   w-30px
                                                   "
                                                   type="button"
                                                   >
                                                   <i class="p-0 fa fa-eye"></i>
                                                   </button>
                                                </router-link>
                                             </li>
                                          </ul>
                                       </td>
                                    </tr>
                                 </tbody>
                                 <tbody v-else>
                                    <tr>
                                       <td colspan="5">No any record(s).</td>
                                    </tr>
                                 </tbody>
                              </table>
                              <div class="col-md-12 col-12">
                                 <div
                                    class="
                                    d-flex
                                    justify-content-end
                                    align-items-center
                                    flex-wrap
                                    "
                                    >
                                    <pagination
                                       :data="reports"
                                       :limit="2"
                                       @pagination-change-page="getReports"
                                       ></pagination>
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade active show" v-if="tab2">
                              <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                                 <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                       <th class="fw-bolder align-middle">#</th>
                                       <th class="fw-bolder align-middle">Name</th>
                                       <th class="fw-bolder align-middle text-center">Test Date</th>
                                       <th class="fw-bolder align-middle text-center">Score(%)</th>
                                       <th class="fw-bolder align-middle text-center">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody
                                    v-if="processing.data && Object.keys(processing.data).length > 0"
                                    >
                                    <tr v-for="(report, key) in processing.data" :key="key">
                                       <td class="align-middle">
                                          <div class="form-group checkbox-list flex-row m-0">
                                             <label class="checkbox">
                                             <input
                                                type="checkbox"
                                                class="me-2"
                                                v-model="selected_report"
                                                :value="report.id"
                                                />
                                             <span></span>
                                             </label>
                                          </div>
                                       </td>
                                       <td class="align-middle">
                                          <span class="d-block">{{ report.name }}</span>
                                          <span
                                             class="d-block text-muted"
                                             >{{ report.email }}</span
                                             >
                                       </td>
                                       <td class="align-middle text-center">{{ report.created_at | formatDate }}</td>
                                       <td class="align-middle text-center">
                                          {{ Math.ceil(report.correct_marks * 100 / report.total_marks) }}
                                       </td>
                                       <td class="align-middle text-center">
                                          <ul class="list-unstyled list-inline m-0">
                                             <li
                                                class="list-inline-item mb-2"
                                                v-tooltip="'Download Report'"
                                                >
                                                <button
                                                   class="
                                                   btn btn-sm btn-light-dark
                                                   p-0
                                                   text-center
                                                   h-30px
                                                   w-30px
                                                   "
                                                   type="button"
                                                   @click="downloadReport(report.id)"
                                                   >
                                                <i class="p-0 fa fa-download"></i>
                                                </button>
                                             </li>
                                             <li
                                                class="list-inline-item mb-2"
                                                title="View"
                                                v-tooltip="'View Report'"
                                                >
                                                <router-link
                                                   :to='{name:"ReportShow",params:{id:report.id}}'
                                                   ><button
                                                   class="
                                                   btn btn-sm btn-light-dark
                                                   p-0
                                                   text-center
                                                   h-30px
                                                   w-30px
                                                   "
                                                   type="button"
                                                   >
                                                   <i class="p-0 fa fa-eye"></i>
                                                   </button>
                                                </router-link>
                                             </li>
                                          </ul>
                                       </td>
                                    </tr>
                                 </tbody>
                                 <tbody v-else>
                                    <tr>
                                       <td colspan="5">No any record(s).</td>
                                    </tr>
                                 </tbody>
                              </table>
                              <div class="col-md-12 col-12">
                                 <div
                                    class="
                                    d-flex
                                    justify-content-end
                                    align-items-center
                                    flex-wrap
                                    "
                                    >
                                    <pagination
                                       :data="processing"
                                       :limit="2"
                                       @pagination-change-page="getReports"
                                       ></pagination>
                                 </div>
                              </div>
                           </div>
                           <div class="tab-pane fade active show" v-if="tab3">
                              <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                                 <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                       <th class="fw-bolder align-middle">Candidate</th>
                                       <th class="fw-bolder align-middle">Test Name</th>
                                       <th class="fw-bolder align-middle">Test Date</th>
                                       <th class="fw-bolder align-middle text-center">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody
                                    v-if="upcomming.data && Object.keys(upcomming.data).length > 0"
                                    >
                                    <tr v-for="(list, key) in upcomming.data" :key="key">
                                       <td class="align-middle">
                                          <span class="d-block">{{ list.name }}</span>
                                          <span
                                             class="d-block text-muted"
                                             >{{ list.email }}</span
                                             >
                                       </td>
                                       <td class="align-middle">
                                          <span class="d-block">{{ list.test.name }}</span>
                                       </td>
                                       <td class="align-middle">
                                          <span
                                             class="d-block"
                                             >{{ list.start | formatFullDate }}</span
                                             >
                                          <span
                                             class="d-block"
                                             >{{ list.end | formatFullDate }}</span
                                             >
                                       </td>
                                       <td class="align-middle text-center text-center">
                                          <ul class="list-unstyled list-inline m-0">
                                             <li
                                                class="list-inline-item mb-2"
                                                v-tooltip="'Delete Candidate From Test'"
                                                >
                                                <button
                                                   class="
                                                   btn btn-sm btn-light-danger
                                                   p-0
                                                   text-center
                                                   h-30px
                                                   w-30px
                                                   "
                                                   type="button"
                                                   @click="deleteCandidateClick(list.id)"
                                                   >
                                                <i class="p-0 fa fa-trash"></i>
                                                </button>
                                             </li>
                                          </ul>
                                       </td>
                                    </tr>
                                 </tbody>
                                 <tbody v-else>
                                    <tr>
                                       <td colspan="4">No any record(s).</td>
                                    </tr>
                                 </tbody>
                              </table>
                              <div class="col-md-12 col-12">
                                 <div
                                    class="
                                    d-flex
                                    justify-content-end
                                    align-items-center
                                    flex-wrap
                                    "
                                    >
                                    <pagination
                                       :data="reports"
                                       :limit="2"
                                       @pagination-change-page="getReports"
                                       ></pagination>
                                 </div>
                              </div>
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
   import Report from "../../apis/Report";
   import Assign from "../../apis/Assign";
   
   export default {
       name:"questions",
       data() {
           return {
               reports: {},
               processing: {},
               upcomming: {},
               loader_spin: false,
               tab1: true,
               tab2: false,
               tab3: false,
               selected_report: [],
               selected_candidate: []
           }
       },
       mounted(){
           this.getReports()
       },
       methods:{
           async getReports(page = 1) {
               this.loader_spin = true
               Report.index(this.$route.params.id, page).then(response => {
                   this.reports = response.data.takers
                   this.processing = response.data.processing
                   this.upcomming = response.data.upcomming
                   this.loader_spin = false
               })
               .catch(error=> {
                   this.reports = []
                   this.loader_spin = false
               });
           },
           async downloadReport(id) {
   
               this.loader_spin = true
               Report.pdf(id).then(response => {
                   const url = window.URL.createObjectURL(new Blob([response.data]));
                   const link = document.createElement('a');
                   link.href = url;
                   link.setAttribute('download', 'report_'+id+'.pdf');
                   document.body.appendChild(link);
                   link.click();
                   this.loader_spin = false
                   this.$toast.success('Pdf download successfully.');
               })
               .catch(error=> {
                   this.loader_spin = false
               });
           },
           async tabsClick(tab) {
               this.selected_report = []
               if(tab == 'tab1') {
                   this.tab1 = true
                   this.tab2 = false
                   this.tab3 = false
               }
   
               if(tab == 'tab2') {
                   this.tab1 = false
                   this.tab2 = true
                   this.tab3 = false
               }
   
               if(tab == 'tab3') {
                   this.tab1 = false
                   this.tab2 = false
                   this.tab3 = true
               }
           },
           async sendReportClick() {
               if(this.selected_report.length == 0) {
                   this.$toast.error('Please select any candidate.')
                   return false
               }
               this.loader_spin = true
               Report.bulkEmailPdf(this.selected_report).then(response => {
                   this.loader_spin = false
                   this.$toast.success(response.data.message)
                   this.selected_report = []
               })
               .catch(error=> {
                   this.loader_spin = false
               });
           },
           async deleteCandidateClick(id) {
               this.loader_spin = true
               if(confirm("Are you sure to delete this candidate from test ?")){
                   Assign.delete(id).then(response => {
                       this.getReports()
                       this.$toast.success(response.data.message)
                       this.loader_spin = false
                   }).catch(error=>{
                       this.loader_spin = false
                   });
               }
           }
       }
   }
</script>