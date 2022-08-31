import axios from "axios";

let Api = axios.create({
  // baseURL: "https://rpm.protacto.com/api"
  baseURL: process.env.BASE_URL
});

Api.defaults.withCredentials = true;

export default Api;
