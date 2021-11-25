import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(form) {
    let token = localStorage.access_token;
    return Api.get("/type", { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/type/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/type/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(type) {
    let token = localStorage.access_token;
    return Api.post("/type", type, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, type) {
    let token = localStorage.access_token;
    return Api.post(`/type/${id}`, type, { headers: { "Authorization" : `Bearer ${token}`} });
  },
};
