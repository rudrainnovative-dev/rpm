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

  async question(id, page) {
    return Api.get(`/online-test/${id}/question?page=`+page);
  },

  async questions(id) {
    return Api.get(`/online-test/${id}/questions`);
  },

  async answerSave(form) {
    return Api.post(`/online-test-answered`, form);
  }
};
