<template>
    <div class="container">
        <div class="row my-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Category <router-link :to='{name:"Category"}' class="btn btn-danger float-right">Back</router-link></h4>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="update">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" v-model="category.name">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Category from "../../apis/Category";

export default {
    name:"update-category",
    data(){
        return {
            category:{
                name:"",
                _method:"patch"
            }
        }
    },
    mounted(){
        this.showCategory()
    },
    methods:{
        async showCategory(){
            Category.show(this.$route.params.id).then(response => {
                const { name } = response.data.category
                this.category.name = name
            }).catch(error=> {
                console.log(error)
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
            })
        },
        async update(){
            Category.update(this.$route.params.id, this.category).then(response => {
                this.$router.push({name:"Category"})
            }).catch(error=>{
                console.log(error)
                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
            })
        }
    }
}
</script>
