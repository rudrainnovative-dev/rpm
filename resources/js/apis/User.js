import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async register(form) {
    await Csrf.getCookie();
    return Api.post("/register", form);
  },

  async login(form) {
    await Csrf.getCookie();
    return Api.post("/login", form);
  },

  async logout(access_token) {
    await Csrf.getCookie();
    localStorage.removeItem("access_token");
    return Api.post("/logout");
  },

  auth() {
    let token = localStorage.access_token;
    return Api.get("/user", { headers: { "Authorization" : `Bearer ${token}`} });
  },
  async profileSave(id, form) {
    let token = localStorage.access_token;
    return Api.post(`/profile/${id}`, form, { headers: { "Authorization" : `Bearer ${token}`} });
  },
  async forget(form) {
    return Api.post("/password/email", form);
  },

  async otpVerification(form) {
    return Api.post("/otp/verify", form);
  },

  async resetPassword(form) {
    return Api.post("/password/reset", form);
  },
  
};
