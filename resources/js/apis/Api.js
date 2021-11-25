import axios from "axios";

let Api = axios.create({
  baseURL: "https://rpm.protacto.com/api"
});

Api.defaults.withCredentials = true;

export default Api;
