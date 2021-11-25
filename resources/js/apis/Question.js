import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async index(page) {
    let token = localStorage.access_token;
    return Api.get("/question?page="+page, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async show(id) {
    let token = localStorage.access_token;
    return Api.get(`/question/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async delete(id) {
    await Csrf.getCookie();
    let token = localStorage.access_token;
    return Api.delete(`/question/${id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async add(question) {
    let token = localStorage.access_token;
    return Api.post("/question", question, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async update(id, question) {
    let token = localStorage.access_token;
    return Api.post(`/question/${id}`, question, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async custom(category_id) {
    let token = localStorage.access_token;
    return Api.get("/question?category_id="+category_id, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async customTest(test_id) {
    let token = localStorage.access_token;
    return Api.get(`/question/test/${test_id}`, { headers: { "Authorization" : `Bearer ${token}`} });
  },

  async uploadExcel(fileData) {
    let token = localStorage.access_token;
    return Api.post("/question/upload", fileData, { headers: { "content-type": "multipart/form-data", "Authorization" : `Bearer ${token}`} });
  }

};
