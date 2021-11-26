<template>
  <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
    <div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background-image: url(/img/login-bg.jpg);">
      <div class="d-flex flex-row-fluid flex-column justify-content-between">
        <router-link class="flex-column-auto mt-5 pb-lg-0 pb-10 display-3 text-white" to="/">RPM</router-link>
        <div class="flex-column-fluid d-flex flex-column justify-content-center">
          <h3 class="mb-5 text-white">Welcome to RPM</h3>
          <p class="font-weight-lighter text-white opacity-80">Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur</p>
        </div>
        <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
          <div class="opacity-70 font-weight-bold text-white">© 2021 RPM</div>
            <ul class="m-0 list-unstyled list-inline">
              <li class="list-inline-item">
                <a href="#" class="text-white">Privacy</a>
              </li>
              <li class="list-inline-item">
                <a href="#" class="text-white ml-10">Legal</a>
              </li>
              <li class="list-inline-item">
                <a href="#" class="text-white ml-10">Contact</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
        <div class="d-flex flex-column-fluid flex-center">
          <div class="login-form login-signin mw-400px w-100 mx-auto">
            <div class="text-center mb-5 mb-lg-10">
              <h1 class="" v-if="form.success == 1">Reset Password</h1>
              <p class="text-muted font-weight-bold" v-if="form.success == 1">Enter email address.</p>
              
              <h1 class="" v-if="form.success == 2">OTP</h1>
              <p class="text-muted font-weight-bold" v-if="form.success == 2">Enter OTP.<small class="d-block">You have received an otp in mail.</small></p>

              <h1 class="" v-if="form.success == 3">Change Password</h1>
              <p class="text-muted font-weight-bold" v-if="form.success == 3">Enter new password.</p>
            </div>
            <form @submit.prevent="send" v-if="form.success == 1">
              <div class="form-group fv-plugins-icon-container mb-7">
                <input v-model="form.email" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" type="email" placeholder="Email ID" required>
                <span class="d-block text-danger" v-if="error">{{ error }}</span>
              </div>
              <div class="form-group fv-plugins-icon-container mb-7">
                <input type="submit" class="btn btn-primary font-weight-bold" value="Submit">
              </div>
            </form>
            <form @submit.prevent="otpVerification" v-if="form.success == 2">
              <div class="form-group fv-plugins-icon-container mb-7">
                <input v-model="form.user_id" class="form-control" type="hidden" name="user_id" required>
                <input  type="text" v-model="form.otp" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" placeholder="OTP" required>
                <span class="d-block text-danger" v-if="error">{{ error }}</span>
              </div>
              <div class="form-group fv-plugins-icon-container mb-7">
                <input type="submit" class="btn btn-primary font-weight-bold" value="Submit">
              </div>
            </form>
            <form @submit.prevent="resetPassword" v-if="form.success == 3">
              <div class="form-group fv-plugins-icon-container mb-7">
                <input v-model="form.user_id" class="form-control" type="hidden" name="user_id" required>
                <input v-model="form.password" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" type="password" name="password" placeholder="New Password" required>
                <span class="d-block text-danger" v-if="errors" v-for="err in errors">{{ err }}</span>
                <span class="d-block text-danger" v-if="error">{{ error }}</span>
              </div>
              <div class="form-group fv-plugins-icon-container mb-7">
                <input v-model="form.password_confirmation" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" type="password" name="password_confirmation" placeholder="Confirm Password" required>
              </div>
              <div class="form-group fv-plugins-icon-container mb-7">
                <input type="submit" class="btn btn-primary font-weight-bold" value="Update">
              </div>
            </form>

            <div class="mt-5 mb-15 mb-lg-0 flex-column-auto justify-content-center py-5">
              <span class="font-weight-bold text-dark-50">Rember Password</span>
               <router-link :to="{ name: 'Login' }" class="text-dark-50 text-hover-primary my-3 mr-2">Sign In</router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import User from "../../apis/User";

export default {
  data() {
    return {
      form: {
        email: '',
        otp: '',
        user_id: '',
        password: '',
        password_confirmation: '',
        success: 1
      },
      error: '',
      errors: []
    }
  },
  methods: {
    async send () {
       User.forget(this.form).then((res) => {
          this.form.success = 2;
          this.form.user_id = res.data.user_id;
          this.error = '';
          this.errors = '';
        }).catch(error => {
          if (error.response.status === 401) {
            this.error = error.response.data.error
          }
        });
    },

    async otpVerification() {
      User.otpVerification(this.form).then((res) => {
          this.form.success = 3;
          this.form.user_id = res.data.user_id;
          this.error = '';
          this.errors = '';
      }).catch(error => {
        if (error.response.status === 422) {
          this.errors = error.response.errors;
        }
        if (error.response.status === 401) {
          this.error =  error.response.data.error;
        }
      });
    },

    async resetPassword() {
      User.resetPassword(this.form).then((res) => {
        this.error = '';
        this.errors = '';
        this.$toast.success(res.data.message);
        this.$router.push({ name: "Login" });
      }).catch(error => {
        if(error.response.status === 422) {
          this.errors = error.response.data.errors;
        }
        if(error.response.status === 401) {
          this.error = error.response.data.error
        }
      });
    }
  }
}
</script>