<template>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                <div data-kt-place="true" data-kt-place-mode="prepend" data-kt-place-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title me-3 mb-5 mb-lg-0 lh-1">
                    <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Update Profile</h1>                               
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-3">
                        <li class="breadcrumb-item text-muted">
                            <router-link :to='{name:"Dashboard"}' class="text-link fs-6">Dashboard</router-link>
                        </li>
                        
                        <li class="breadcrumb-item text-muted">
                            <p class="text-muted m-0 fs-6">Profile</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form  @submit.prevent="update">
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">User Name<span class="text-danger">*</span></label>
                            <input type="text" data-validation="required" class="form-control form-control-solid form-control-sm"  placeholder="Enter User Name" v-model="profile.user_name" required/>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">Old Password</label>
                            <input type="password" class="form-control form-control-solid form-control-sm"  placeholder="Enter Test Name" data-validation-optional="true" data-validation="length" data-validation-length="min8" v-model="profile.old_password" />
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">New Password</label>
                            <input type="password" class="form-control form-control-solid form-control-sm"  placeholder="Enter Test Name" data-validation-optional="true" data-validation="length" data-validation-length="min8"  v-model="profile.password" />
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">Confirm Password</label>
                            <input type="password" class="form-control form-control-solid form-control-sm"  placeholder="Enter Test Name" data-validation-optional="true"  data-validation="length" data-validation-length="min8"  v-model="profile.confirm_password" />
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <button type="submit" class="btn btn-dark btn-sm float-right">Update</button>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import User from '../apis/User';
    export default {
        name:"profile",
        data(){
            return {

                disabled: false,
                loader_spin: true,
                profile:{
                    user_name: localStorage.user_name,
                    old_password:'',
                    password:'',
                    confirm_password:'',
                }
                

            }
        },
        mounted() {
            

        },
        methods:{
            async update(){
                if(this.profile.old_password === '' || this.profile.old_password && this.profile.password   &&   this.profile.password == this.profile.confirm_password ){

                    this.disabled = true
                    User.profileSave(this.$route.params.id, this.profile).then(response => {  
                        console.log(response);
                        if(response.data.status){

                            this.$toast.success(response.data.message);
                            localStorage.setItem('user_name',response.data.user_name);
                            this.profile.old_password ='',
                            this.profile.password ='',
                            this.profile.confirm_password ='',
                            this.disabled = false
                        }else{
                            this.$toast.error(response.data.message);
                            this.disabled = false
                        }
                    }).catch(error=>{
                        this.disabled = false
                    })
                }else{

                    if(this.profile.password != '' && this.profile.confirm_password != '' && this.profile.password !== this.profile.confirm_password){

                        this.$toast.error('Your password and confirmation password do not match');
                    }else{
                        this.$toast.error('Password and confirm password required !');
                    }
                }
                
            } 
        }
    }
</script>