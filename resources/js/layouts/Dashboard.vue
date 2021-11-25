<template>
    <div class="page d-flex flex-row flex-column-fluid">
        
        <Navigation />
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            <HeaderTop />
            <router-view />     
        </div>
    </div>
</template>

<script>
    import Navigation from "../components/Navigation.vue";
    import HeaderTop from "../components/Header.vue";
    export default {
        components: {
            Navigation,
            HeaderTop
        }
    };
</script>