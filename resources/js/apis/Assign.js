import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(page, search) {
    let token = localStorage.access_token;
    return Api.get("/assign?page="+page+"&search="+search, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/assign/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async showCandidate(id) {
    let token = localStorage.access_token;
    return Api.get(`/candidate/${id}/show`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/assign/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(assign) {
    let token = localStorage.access_token;
    return Api.post("/assign", assign, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, assign) {
    let token = localStorage.access_token;
    return Api.put(`/assign/${id}`, assign, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async getAllTest() {
    let token = localStorage.access_token;
    return Api.get("/all_tests", { headers: { "Authorization" : `Bearer ${token}`} });
  },

  
  


};
