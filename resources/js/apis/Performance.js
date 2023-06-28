import Api from "./Api";
import Csrf from "./Csrf";

export default {
  
  async index(page) {
    let token = localStorage.access_token;
    return Api.get("/performance?page="+page, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/performance/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/performance/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(performance) {
    let token = localStorage.access_token;
    return Api.post("/performance", performance, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, performance) {
    let token = localStorage.access_token;
    return Api.post(`/performance/${id}`, performance, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async type() {
    let token = localStorage.access_token;
    return Api.get("/performance-type", { headers: { "Authorization" : `Bearer ${token}`} });
  },

};

