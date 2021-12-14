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

  async question(id, category_id, page) {
    return Api.get(`/online-test/${id}/question?category_id=`+category_id+`&page=`+page);
  },

  async questions(id, category_id) {
    return Api.get(`/online-test/${id}/questions?category_id=`+category_id);
  },

  async answerSave(form) {
    return Api.post(`/online-test-answered`, form);
  },

  async takerUpdate(form) {
    return Api.post(`/online-test-taker`, form);
  },

  async takerSnap(form) {
    return Api.post(`/online-test-snap`, form);
  },

};
