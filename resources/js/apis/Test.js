import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(page) {
    let token = localStorage.access_token;
    return Api.get("/test?page="+page, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/test/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/test/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(test) {
    let token = localStorage.access_token;
    return Api.post("/test", test, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, test) {
    let token = localStorage.access_token;
    return Api.post(`/test/${id}`, test, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async criterias() {
    let token = localStorage.access_token;
    return Api.get("/criterias", { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async getAll() {
    let token = localStorage.access_token;
    return Api.get("/test?test=1", { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async duplicateTest(duplicateTest) {
    let token = localStorage.access_token;
    return Api.post("/test/duplicate", duplicateTest, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async testQuestions(test_id, page) {
    let token = localStorage.access_token;
    return Api.get(`/test/${test_id}/questions?page=`+page, { headers: { "Authorization" : `Bearer ${token}`} });
  },

};
