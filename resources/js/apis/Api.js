import axios from "axios";

let Api = axios.create({
  // baseURL: "https://rpm.protacto.com/api"
  baseURL: "http://192.168.1.30:8000/api"
});

Api.defaults.withCredentials = true;

export default Api;
