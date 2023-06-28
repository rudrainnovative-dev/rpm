import axios from "axios";
let Api = axios.create({  
   baseURL: process.env.MIX_API_URL
});

Api.defaults.withCredentials = true;


export default Api;
