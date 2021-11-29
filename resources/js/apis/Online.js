import Api from "./Api";

export default {
  async index(id) {
    return Api.get(`/online-test/${id}`);
  },


};
