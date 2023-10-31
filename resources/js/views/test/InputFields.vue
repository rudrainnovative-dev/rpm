<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Registration Fields for Candidate</h1>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-5">Dashboard</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Test"}' class="text-link fs-6">All Tests</router-link>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Registration Fields for Candidate</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <div class="row mt-7">
                    <div class="col-md-12 col-12">
                        <div class="d-flex align-items-center justify-content-md-end mb-4">
                            <router-link :to='{name:"Test"}' class="btn btn-sm btn-secondary router-link-active">Back to List</router-link>
                        </div>
                    </div>
                    <div class="col-md-12 col-12 d-flex">
                        <div class="card card-xl-stretch mb-xl-8 w-100">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                            <table class="table table-rounded table-striped border gy-7 gs-7 m-0 m-0">
                                                <thead>
                                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom border-gray-200">
                                                        <th class="fw-bolder align-middle">Field</th>
                                                        <th class="fw-bolder align-middle text-center">Required</th>
                                                        <th class="fw-bolder align-middle text-center">Allow</th>
                                                    </tr>
                                                </thead>
                                                <tbody v-if="this.registation_fields.length > 0">
                                                    <tr v-for="(field,key) in this.registation_fields" :key="key">
                                                        <td class="align-middle">{{ field.field }}</td>
                                                        <td class="align-middle text-center">
                                                            <label class="checkbox justify-content-center" v-if="field.field != 'Name' && field.field != 'Email'">
                                                                <input type="checkbox" v-model="field.required" :value="{id: field.id, required: field.required, allow: field.allow}" v-on:change="update($event, key)" :disabled="disabled">
                                                                <span></span>
                                                            </label>
                                                            <label class="checkbox justify-content-center" v-else>
                                                                <input type="checkbox" v-model="field.required" :value="{id: field.id, required: field.required, allow: field.allow}" disabled="disabled">
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <label class="checkbox justify-content-center" v-if="field.field != 'Name' && field.field != 'Email'">
                                                                <input type="checkbox" v-model="field.allow" :value="{id: field.id, required: field.required, allow: field.allow}" v-on:change="update($event, key)" :disabled="disabled">
                                                                <span></span>
                                                            </label>
                                                            <label class="checkbox justify-content-center" v-else>
                                                                <input type="checkbox" v-model="field.allow" :value="{id: field.id, required: field.required, allow: field.allow}" disabled="disabled">
                                                                <span></span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tbody v-else>
                                                    <tr>
                                                        <td colspan = '3'>No any field.</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
    </div>
</template>

<script>

    import RegistationField from "../../apis/Registation";

    export default {
        name:"registation-fields",
        data(){
            return {
                disabled: false,
                loader_spin: true,
                registation_fields: [],
            }
        },
        mounted() {
            this.getFields()
        },
        methods:{
            async getFields() {
                RegistationField.show(this.$route.params.id).then(response => {
                    this.registation_fields = response.data.registation_fields
                    this.loader_spin = false
                }).catch(error=> {
                    this.loader_spin = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                })
            },
            async update(event, key) {
                this.disabled = true
                this.registation_fields[key]['_method'] = "patch"
                RegistationField.update(this.$route.params.id, this.registation_fields[key]).then(response => {
                    console.log(response)
                    this.$toast.success(response.data.message);
                    this.disabled = false
                }).catch(error=>{
                    this.disabled = false
                    if (error.response.status === 401) {
                        this.$toast.error(error.response.data.message);
                        this.$router.push({ name: "Login" });
                    }
                })
            }
        }
    }
</script>
