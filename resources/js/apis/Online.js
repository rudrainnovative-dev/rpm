import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(id) {
    return Api.get(`/online-test/${id}`);
  },

  async register(form, id) {
    await Csrf.getCookie();
    return Api.post(`/online-test/${id}`, form);
  },

};
