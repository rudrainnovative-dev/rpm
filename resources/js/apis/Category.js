import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(form) {
    let token = localStorage.access_token;
    return Api.get("/category", { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/category/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/category/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(category) {
    let token = localStorage.access_token;
    return Api.post("/category", category, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, category) {
    let token = localStorage.access_token;
    return Api.post(`/category/${id}`, category, { headers: { "Authorization" : `Bearer ${token}`} });
  },
};
