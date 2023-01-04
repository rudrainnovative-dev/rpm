<template>
  <div id="header-main">
    <div id="kt_header" class="header align-items-stretch">
      <div
        class="
          container-fluid
          d-flex
          align-items-stretch
          justify-content-between
        "
      >
        <div
          class="d-flex align-items-center d-lg-none ms-n3 me-1"
          title="Show aside menu"
        >
          <div
            class="btn btn-icon btn-active-light-primary"
            id="sidebar_aside_mobile_toggle"
          >
            <span class="svg-icon svg-icon-2x mt-1">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                width="24px"
                height="24px"
                viewBox="0 0 24 24"
                version="1.1"
              >
                <g
                  stroke="none"
                  stroke-width="1"
                  fill="none"
                  fill-rule="evenodd"
                >
                  <rect x="0" y="0" width="24" height="24"></rect>
                  <rect
                    fill="#000000"
                    x="4"
                    y="5"
                    width="16"
                    height="3"
                    rx="1.5"
                  ></rect>
                  <path
                    d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L18.5,10 C19.3284271,10 20,10.6715729 20,11.5 C20,12.3284271 19.3284271,13 18.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z"
                    fill="#000000"
                    opacity="0.3"
                  ></path>
                </g>
              </svg>
            </span>
          </div>
        </div>
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
          <a href="#" class="d-lg-none"> </a>
        </div>
        <div class="d-flex w-100 justify-content-between align-items-center">
          <div class="min-w-lg-300px"></div>
          <div class="d-flex align-items-center">
            <div class="d-flex align-items-center">
              <p class="m-0 fw-bold"><router-link title="Profile" :to='{name:"Profile",params:{id:user_token}}'>{{ user_name }}</router-link></p>
            </div>
            <span class="h-20px border-gray-200 border-start mx-5"></span>
            <div class="d-flex align-items-center">
              <button
                type="button"
                class="btn btn-light-primary btn-sm px-3 btn-icon"
                title="Logout"
                v-if="isLoggedIn"
                v-on:click="logout"
              >
                <span class="svg-icon"
                  ><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Sign-out.svg--><svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="24px"
                    height="24px"
                    viewBox="0 0 24 24"
                    version="1.1"
                  >
                    <g
                      stroke="none"
                      stroke-width="1"
                      fill="none"
                      fill-rule="evenodd"
                    >
                      <rect x="0" y="0" width="24" height="24" />
                      <path
                        d="M14.0069431,7.00607258 C13.4546584,7.00607258 13.0069431,6.55855153 13.0069431,6.00650634 C13.0069431,5.45446114 13.4546584,5.00694009 14.0069431,5.00694009 L15.0069431,5.00694009 C17.2160821,5.00694009 19.0069431,6.7970243 19.0069431,9.00520507 L19.0069431,15.001735 C19.0069431,17.2099158 17.2160821,19 15.0069431,19 L3.00694311,19 C0.797804106,19 -0.993056895,17.2099158 -0.993056895,15.001735 L-0.993056895,8.99826498 C-0.993056895,6.7900842 0.797804106,5 3.00694311,5 L4.00694793,5 C4.55923268,5 5.00694793,5.44752105 5.00694793,5.99956624 C5.00694793,6.55161144 4.55923268,6.99913249 4.00694793,6.99913249 L3.00694311,6.99913249 C1.90237361,6.99913249 1.00694311,7.89417459 1.00694311,8.99826498 L1.00694311,15.001735 C1.00694311,16.1058254 1.90237361,17.0008675 3.00694311,17.0008675 L15.0069431,17.0008675 C16.1115126,17.0008675 17.0069431,16.1058254 17.0069431,15.001735 L17.0069431,9.00520507 C17.0069431,7.90111468 16.1115126,7.00607258 15.0069431,7.00607258 L14.0069431,7.00607258 Z"
                        fill="#000000"
                        fill-rule="nonzero"
                        opacity="0.3"
                        transform="translate(9.006943, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-9.006943, -12.000000) "
                      />
                      <rect
                        fill="#000000"
                        opacity="0.3"
                        transform="translate(14.000000, 12.000000) rotate(-270.000000) translate(-14.000000, -12.000000) "
                        x="13"
                        y="6"
                        width="2"
                        height="12"
                        rx="1"
                      />
                      <path
                        d="M21.7928932,9.79289322 C22.1834175,9.40236893 22.8165825,9.40236893 23.2071068,9.79289322 C23.5976311,10.1834175 23.5976311,10.8165825 23.2071068,11.2071068 L20.2071068,14.2071068 C19.8165825,14.5976311 19.1834175,14.5976311 18.7928932,14.2071068 L15.7928932,11.2071068 C15.4023689,10.8165825 15.4023689,10.1834175 15.7928932,9.79289322 C16.1834175,9.40236893 16.8165825,9.40236893 17.2071068,9.79289322 L19.5,12.0857864 L21.7928932,9.79289322 Z"
                        fill="#000000"
                        fill-rule="nonzero"
                        transform="translate(19.500000, 12.000000) rotate(-90.000000) translate(-19.500000, -12.000000) "
                      />
                    </g></svg
                  ><!--end::Svg Icon--></span
                >
              </button>
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
isLoggedIn: false,
user_name: localStorage.user_name,
user_token: localStorage.user_id,
};
},
mounted() {
this.$root.$on("login", () => {
this.isLoggedIn = true;
});
this.isLoggedIn = !!localStorage.getItem("auth");
},
methods: {
logout() {
User.logout().then(() => {
localStorage.removeItem("auth");
localStorage.removeItem("access_token");
localStorage.removeItem("user_name");
this.isLoggedIn = false;
this.$router.push({ name: "Login" });
});
}
}
};
</script>
