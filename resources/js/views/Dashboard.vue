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
                <h3 class="card-title fw-bolder text-dark m-0">Upcoming Tests</h3>
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
                          <span class="badge badge-light my-1">{{ test.start.split(' ')[0] }}</span>
                          <span class="badge badge-light my-1">{{ test.end.split(' ')[0] }}</span>
                        </td>
                        <td class="align-middle">{{ test.test.name }}</td>
                      </tr>
                  </tbody>
                  <tbody v-else>
                      <tr>
                        <td colspan="3" class="align-middle">No any Upcoming Tests.</td>
                      </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 col-12 d-flex">
            <div class="card card-xl-stretch mb-xl-8 w-100">
              <div class="card-header border-0">
                <h3 class="card-title fw-bolder text-dark m-0">Recently Created Tests</h3>
              </div>
            <div class="separator mb-2"></div>
            <div class="card-body">
              <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                <thead>
                  <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th class="fw-bolder align-middle">Test Name</th>
                    <th class="fw-bolder align-middle">No. of Questions</th>
                    <th class="fw-bolder align-middle">Action</th>
                  </tr>
                </thead>
                <tbody v-if="recent_test.length > 0">
                  <tr v-for="test in recent_test">
                    <td class="align-middle">{{ test.name }}</td>
                    <td class="align-middle">{{ test.test_questions.length }}</td>
                    <td class="align-middle">
                      <router-link :to='{name:"OnlineTest",params:{id:test.public_id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" v-tooltip="tooltip.show"><i class="p-0 fa fa-eye"></i></button></router-link>
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
              <h3 class="card-title fw-bolder text-dark m-0">Completed Tests</h3>
            </div>
            <div class="separator mb-2"></div>
              <div class="card-body">
                <table class="table table-rounded table-striped border gy-7 gs-7 m-0">
                  <thead>
                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                      <th class="fw-bolder align-middle">Candidate Name</th>
                      <th class="fw-bolder align-middle">Test Date</th>
                      <th class="fw-bolder align-middle">Test Name</th>
                      <th class="fw-bolder align-middle">View Report</th>
                    </tr>
                  </thead>
                  <tbody v-if="completed_test.length > 0">
                    <tr v-for="test in completed_test">
                      <td class="align-middle">{{ test.name }}</td>
                      <td class="align-middle">{{ test.created_at.split('T')[0] }}</td>
                      <td class="align-middle">{{ test.test_name }}</td>
                      <td class="align-middle">
                        <router-link :to='{name:"ReportShow",params:{id:test.id}}'><button class="btn btn-sm btn-light-dark p-0 text-center h-30px w-30px" type="button" v-tooltip="'Show Report'"><i class="p-0 fa fa-eye"></i></button></router-link>
                      </td>
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
      });
    }
  }

};
</script>