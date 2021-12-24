<template>
	<div id="header-main">
		<div id="kt_header" class="header align-items-stretch">
			<div class="container-fluid d-flex align-items-stretch justify-content-between">
				<div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
					<div class="btn btn-icon btn-active-light-primary" id="sidebar_aside_mobile_toggle">
						<span class="svg-icon svg-icon-2x mt-1">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24"></rect>
									<rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"></rect>
									<path d="M5.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 Z M5.5,10 L18.5,10 C19.3284271,10 20,10.6715729 20,11.5 C20,12.3284271 19.3284271,13 18.5,13 L5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z" fill="#000000" opacity="0.3"></path>
								</g>
							</svg>
						</span>
					</div>
				</div>
				<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
					<a href="#" class="d-lg-none">
						
					</a>
				</div>
				<div class="d-flex w-100 justify-content-between align-items-center">
					<div class="min-w-lg-300px">
						
					</div>
					<div class="d-flex align-items-center">
						<div class="d-flex align-items-center">
							<p class="m-0">{{ user_name }}</p>
						</div>
						<span class="h-20px border-gray-200 border-start mx-5"></span>
						<div class="d-flex align-items-center">
							<button type="button" class="btn btn-light-primary btn-sm px-3" title="Logout" v-if="isLoggedIn" v-on:click="logout">
								<span class="svg-icon svg-icon-primary svg-icon-2 m-0">
									<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
										 viewBox="0 0 384.971 384.971" style="enable-background:new 0 0 384.971 384.971;" xml:space="preserve">
										<g id="Sign_Out">
											<path d="M180.455,360.91H24.061V24.061h156.394c6.641,0,12.03-5.39,12.03-12.03s-5.39-12.03-12.03-12.03H12.03
												C5.39,0.001,0,5.39,0,12.031V372.94c0,6.641,5.39,12.03,12.03,12.03h168.424c6.641,0,12.03-5.39,12.03-12.03
												C192.485,366.299,187.095,360.91,180.455,360.91z"/>
											<path d="M381.481,184.088l-83.009-84.2c-4.704-4.752-12.319-4.74-17.011,0c-4.704,4.74-4.704,12.439,0,17.179l62.558,63.46H96.279
												c-6.641,0-12.03,5.438-12.03,12.151c0,6.713,5.39,12.151,12.03,12.151h247.74l-62.558,63.46c-4.704,4.752-4.704,12.439,0,17.179
												c4.704,4.752,12.319,4.752,17.011,0l82.997-84.2C386.113,196.588,386.161,188.756,381.481,184.088z"/>
										</g>
									</svg>
								</span>
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
	      user_name: localStorage.user_name
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