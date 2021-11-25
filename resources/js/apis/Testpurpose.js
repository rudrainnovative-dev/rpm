import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(form) {
    let token = localStorage.access_token;
    return Api.get("/testpurpose", { headers: { "Authorization" : `Bearer ${token}`} });
  },

};
