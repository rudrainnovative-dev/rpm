import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index() {
    let token = localStorage.access_token;
    return Api.get("/dashboard", { headers: { "Authorization" : `Bearer ${token}`} });
  },


};
