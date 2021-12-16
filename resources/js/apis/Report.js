import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(id, page) {
    let token = localStorage.access_token;
    return Api.get(`/report/${id}/test?page=`+page, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/report/${id}/show`, { headers: { "Authorization" : `Bearer ${token}`} });
  },
  
  async pdf(id) {
    let token = localStorage.access_token;
    return Api.get(`/report/${id}/pdf`, { responseType: 'blob', headers: { "Authorization" : `Bearer ${token}`} });
  },

};
