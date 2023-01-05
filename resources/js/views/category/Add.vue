<template>
    <div class="container">
        <div class="row my-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Question Category <router-link :to='{name:"Category"}' class="btn btn-danger float-right">Back</router-link></h4>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="create">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" v-model="category.name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
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
    name:"add-category",
    data(){
        return {
            category:{
                name:""
            }
        }
    },
    methods:{
        async create(){
            Category.add(this.category).then(response => {
                this.$router.push({name:"Category"})
            }).catch(error=> {

                if (error.response.status === 401) {
                    this.$toast.error(error.response.data.message);
                    this.$router.push({ name: "Login" });
                }
            })
        }
    }
}
</script>