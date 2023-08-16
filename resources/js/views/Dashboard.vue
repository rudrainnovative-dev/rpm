<template>
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
      <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center me-3 flex-wrap mb-5 mb-lg-0 lh-1">
          <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Dashboard</h1>
        </div>
      </div>
    </div>
    
    <div class="post d-flex flex-column-fluid" id="kt_post">
      <div id="kt_content_container" class="container">
        <div class="row">
          <div class="col-md-6 col-12 d-flex">
            <div class="card card-xl-stretch mb-xl-8 w-100">
              <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark m-0">Upcoming Test(s)</h3>
              </div>
              <div class="separator mb-2"></div>
              <div class="card-body">
                <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                  <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                      <th class="fw-bolder align-middle">Candidate</th>
                      <th class="fw-bolder align-middle">Test Date</th>
                      <th class="fw-bolder align-middle">Test Name</th>
                    </tr>
                  </thead>
                  <tbody v-if="upcomming_test.length > 0">
                    <tr v-for="test in upcomming_test">
                      <td class="align-middle">{{ test.email }}</td>
                      <td class="align-middle">
                        <span class="badge badge-light my-1">{{ test.start | formatDate }}</span>
                        <span class="badge badge-light my-1">{{ test.end | formatDate }}</span>
                      </td>
                      <td class="align-middle">{{ test.test.name }}</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="3" class="align-middle">No any Upcoming Test(s).</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 col-12 d-flex">
            <div class="card card-xl-stretch mb-xl-8 w-100">
              <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark m-0">Recently Created Test(s)</h3>
              </div>
              <div class="separator mb-2"></div>
              <div class="card-body">
                <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                  <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                      <th class="fw-bolder align-middle">Test Name</th>
                      <th class="fw-bolder align-middle text-center">No. of Questions</th>
                      <th class="fw-bolder align-middle text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody v-if="recent_test.length > 0">
                    <tr v-for="test in recent_test">
                      <td class="align-middle">{{ test.name }}</td>
                      <td class="align-middle text-center">{{ test.test_questions.length }}</td>
                      <td class="align-middle text-center">
                        <router-link :to='{name:"OnlineTestPreview",params:{id:test.public_id, user:"admin"}}' target="_blank"><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" v-tooltip="tooltip.show"><i class="p-0 fa fa-eye"></i></button></router-link>
                      </td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="3" class="align-middle">No any record(s).</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-12 d-flex">
            <div class="card card-xl-stretch mb-xl-8 w-100">
              <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark m-0">Completed Test(s)</h3>
              </div>
              <div class="separator mb-2"></div>
              <div class="card-body">
                <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                  <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                      <th class="fw-bolder align-middle">Candidate Name</th>
                      <th class="fw-bolder align-middle">Test Date</th>
                      <th class="fw-bolder align-middle">Test Name</th>
                      <th class="fw-bolder align-middle text-center">View Report</th>
                    </tr>
                  </thead>
                  <tbody v-if="completed_test.length > 0">
                    <tr v-for="test_taker in completed_test">
                      <td class="align-middle">{{ test_taker.name }}</td>
                      <td class="align-middle">{{ test_taker.created_at | formatDate }}</td>
                      <td class="align-middle">{{ test_taker.test_name }}</td>
                      <td class="align-middle text-center" v-if="test_taker.status==2">
                        <ul class="list-unstyled list-inline m-0">
                          <li class="list-inline-item">
                            <router-link :to='{name:"ReportShow",params:{id:test_taker.id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" v-tooltip="'Show Report'"><i class="p-0 fa fa-eye"></i></button></router-link>
                          </li>
                          <li class="list-inline-item">
                            <button class="btn btn-sm btn-light-primary p-0 text-center h-30px w-30px" type="button" @click="downloadReport(test_taker.id)" v-tooltip="'Download Report'"><i class="p-0 fa fa-download"></i></button>
                          </li>
                        </ul>
                      </td>
                      <td v-else class="align-middle text-center">pending</td>
                    </tr>
                  </tbody>
                  <tbody v-else>
                    <tr>
                      <td colspan="4" class="align-middle">No any record(s).</td>
                    </tr>
                  </tbody>
                </table>
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
  import Dashboard from "../apis/Dashboard";
  import Report from "../apis/Report";
  export default {
    data() {
      return {
        recent_test: {},
        upcomming_test: {},
        completed_test: {},
        loader_spin: true,
        tooltip: {
          show: 'Show',
          edit: 'Edit',
          delete: 'Delete'
        }
      };
    },
    mounted() {
      this.getData()
    },
    methods: {
      async getData() {
        Dashboard.index().then(response => {
          this.recent_test = response.data.tests
          this.upcomming_test = response.data.upcomming_test
          this.completed_test = response.data.completed_test
          this.loader_spin = false
        }).catch(error=> {
          this.loader_spin = false
          if (error.response.status === 401) {
            this.$toast.error(error.response.data.message);
            this.$router.push({ name: "Login" });
          }
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
          if (error.response.status === 401) {
            this.$toast.error(error.response.data.message);
            this.$router.push({ name: "Login" });
          }
        });
      },
    }

  };
</script>