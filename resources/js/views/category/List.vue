<template>
  <div class="container">
    <div class="row my-5">
        <div class="col-12 mb-2 text-right">
            <router-link :to='{name:"CategoryAdd"}' class="btn btn-primary border-0">Create</router-link>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Question Category</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody v-if="categories.length > 0">
                                <tr v-for="(category,key) in categories" :key="key">
                                    <td>{{ category.id }}</td>
                                    <td>{{ category.name }}</td>
                                    <td>
                                        <router-link :to='{name:"CategoryEdit",params:{id:category.id}}' class="btn btn-success">Edit</router-link>
                                        <button type="button" @click="deleteCategory(category.id)" class="btn btn-danger">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3" align="center">No Categories Found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>

import Category from "../../apis/Category";

export default {
    name:"categories",
    data(){
        return {
            categories:[]
        }
    },
    mounted(){
        this.getCategories()
    },
    methods:{
        async getCategories() {
          Category.index().then(response => {
            this.categories = response.data.categories;
          })
          .catch(error=> {
            this.categories = []
            if (error.response.status === 401) {
                this.$toast.error(error.response.data.message);
                this.$router.push({ name: "Login" });
            }
          });
        },
        deleteCategory(id) {
            if(confirm("Are you sure to delete this category ?")){
                Category.delete(id).then(response => {
                    this.getCategories()
                }).catch(error=>{
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