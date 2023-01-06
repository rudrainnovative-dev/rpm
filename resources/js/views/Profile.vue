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
                            <label class="mb-3 fw-bolder fs-5">Name<span class="text-danger">*</span></label>
                            <input type="text" data-validation="required" class="form-control form-control-sm"  placeholder="Enter User Name" v-model="profile.user_name" required/>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">Email</label>
                            <input type="email" class="form-control form-control-solid form-control-sm"  placeholder="Enter Email Name" v-model="user_email" :disabled="user_email" />
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">New Password</label>
                            <input type="password" class="form-control  form-control-sm"  placeholder="Enter New Password" data-validation-optional="true" data-validation="length" data-validation-length="min8"  v-model="profile.password" />
                            <small class="text-info">Leave it empty, if you don't want to change password</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <div class="form-group">
                            <label class="mb-3 fw-bolder fs-5">Confirm Password</label>
                            <input type="password" class="form-control  form-control-sm"  placeholder="Enter Confirm Password" data-validation-optional="true"  data-validation="length" data-validation-length="min8"  v-model="profile.confirm_password" />
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    
                </div>
                <div class="row">
                    <div class="col-md-4 col-12"></div>
                    <div class=" col-md-4 col-12">
                        <button type="submit" :disabled="disabled" class="btn btn-dark btn-sm float-right">Update</button>
                    </div>
                    
                    <div class="col-md-4 col-12"></div>
                    <Header :name="user_name" />
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import User from '../apis/User';
    import Header from '../components/Header.vue'
    export default {
        name:"profile",
        components:{
            Header
        },
        data(){
            return {

                disabled: false,
                loader_spin: true,
                user_name:'',
                profile:{
                    user_name: localStorage.user_name,
                    password:'',
                    confirm_password:'',
                },
                user_email:localStorage.user_email,
                

            }
        },
        mounted() {
           this.user_name = localStorage.getItem('user_name');

        },
        methods:{
            async update(){

                this.disabled = true

                if(this.profile.password && this.profile.confirm_password && this.profile.password !== this.profile.confirm_password || this.profile.confirm_password =='' && this.profile.password || this.profile.confirm_password  && this.profile.password ==''){

                    this.$toast.error('New password and confirmation password does not match');
                    this.disabled = false;
                    
                }else{

                    
                    User.profileSave(this.$route.params.id, this.profile).then(response => {  
    
                        
                        localStorage.setItem('user_name',response.data.user_name);
                        this.user_name =response.data.user_name;
                        this.profile.password ='',
                        this.profile.confirm_password ='',
                        this.disabled = false
                        this.$toast.success(response.data.message);
                        // this.$router.push({name:"Profile"})  

                        
                    }).catch(error=>{

                        this.disabled = false
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors;
                        }

                        if (error.response.status === 401) {
                            this.$toast.error(error.response.data.message);
                            this.$router.push({ name: "Login" });
                        }
                    })
                }


            }
                
        } 
    }
</script>