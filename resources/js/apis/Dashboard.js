import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async recentTest() {
    let token = localStorage.access_token;
    return Api.get("/dashboard/recent/test", { headers: { "Authorization" : `Bearer ${token}`} });
  },


};
