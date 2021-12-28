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
          <div class="opacity-70 font-weight-bold text-white">© {{ new Date().getFullYear() }} RPM</div>
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
              <h1 class="">Sign In</h1>
              <p class="text-muted font-weight-bold">Enter your email and password</p>
            </div>
            <div class="form-group fv-plugins-icon-container mb-7">
              <input type="email" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" v-model="form.email" placeholder="Email Address" autocomplete="off"/>
              <span class="text-danger" v-if="errors.email">{{ errors.email[0] }}</span>
            </div>
            <div class="form-group fv-plugins-icon-container mb-7">
              <input type="password" class="form-control form-control-solid h-auto py-5 px-6 fw-normal" v-model="form.password" placeholder="Password" />
              <span class="text-danger" v-if="errors.password">{{ errors.password[0] }}</span>
            </div>
            <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
              <router-link :to="{ name: 'ForgetPassword' }" class="text-dark-50 text-hover-primary my-3 mr-2">Forgot Password ?</router-link>

              <button @click.prevent="login" class="btn btn-primary font-weight-bold" :disabled="disabled"><span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-if="disabled"></span> Sign In</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import User from "../apis/User";
export default {
  data() {
    return {
      form: {
        email: "",
        password: ""
      },
      errors: [],
      disabled: false
    };
  },
  methods: {
    login() {
      this.disabled = true

      User.login(this.form)
        .then((res) => {
          this.errors = [];
          this.$root.$emit("login", true);
          localStorage.setItem("auth", "true");
          localStorage.setItem("access_token", res.data.access_token);
          localStorage.setItem("user_name", res.data.user_name);
          this.$router.push({ name: "Dashboard" });
          this.disabled = false
        })
        .catch(error => {
          this.disabled = false
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
          }

          if (error.response.status === 401) {
            this.$toast.error(error.response.data.message);
          }

        });
    }
  }
};
</script>