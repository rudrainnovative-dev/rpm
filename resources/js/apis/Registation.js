import Api from "./Api";
import Csrf from "./Csrf";

export default {

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/registation-field/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/registation-field/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(fields) {
    let token = localStorage.access_token;
    return Api.post("/registation-field", fields, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, fields) {
    let token = localStorage.access_token;
    return Api.post(`/registation-field/${id}`, fields, { headers: { "Authorization" : `Bearer ${token}`} });
  },

};
