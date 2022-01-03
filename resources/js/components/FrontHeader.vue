<template>
    <div class="logo w-100">
        <a href="javascript:void(0);" class="mt-5 pb-lg-0 pb-10 display-4 text-dark">
        	<img :src="logo" />
        </a>
    </div>
</template>
<script>
	export default {
		name: 'front-logo',
		data() {
			return {
				logo: '../../../img/front_logo.png'
			}
		}
	}
</script>