import Vue from "vue";
import VueRouter from "vue-router";

import PublicLayout from "./layouts/Public.vue";
import DashboardLayout from "./layouts/Dashboard.vue";

import Welcome from "./views/Welcome.vue";
import Login from "./views/Login.vue";
import ForgetPassword from "./views/password/Email.vue";

// import Register from "./views/Register.vue";
import Dashboard from "./views/Dashboard.vue";
import Category from "./views/category/List.vue";
import CategoryAdd from "./views/category/Add.vue";
import CategoryEdit from "./views/category/Edit.vue";

import Question from "./views/questions/List.vue";
import QuestionAdd from "./views/questions/Add.vue";
import QuestionEdit from "./views/questions/Edit.vue";
import QuestionShow from "./views/questions/Show.vue";

import Test from "./views/test/List.vue";
import TestAdd from "./views/test/Add.vue";
import TestEdit from "./views/test/Edit.vue";
import TestShow from "./views/test/Show.vue";
import TestQuestion from "./views/test/Question.vue";
import TestUserField from "./views/test/InputFields.vue";

import PerformanceAdd from "./views/performance/Add.vue";
import PerformanceShow from "./views/performance/Show.vue";

import AssignList from "./views/assign/List.vue";
import AssignAdd from "./views/assign/Add.vue";
import AssignSend from "./views/assign/Send.vue";
import AssignSendOne from "./views/assign/SendOne.vue";

import OnlineTest from "./views/front/Test.vue";

import PageNotFound from "./views/PageNotFound.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: Welcome,
        name: 'Welcome',
        meta: { title: 'RPM' }
      }
    ],
  },
  {
    path: '/login',
    component: PublicLayout,
    children: [
      {
        path: '',
        component: Login,
        name: 'Login',
        meta: { title: 'Login | RPM', guestOnly: true }
      }
    ],
  },
  // {
  //   path: '/register',
  //   component: PublicLayout,
  //   children: [
  //     {
  //       path: '',
  //       component: Register,
  //       name: 'Register',
  //       meta: { title: 'Register | RPM', guestOnly: true }
  //     }
  //   ],
  // },
  {
    path: '/forget-password',
    component: PublicLayout,
    children: [
      {
        path: '',
        component: ForgetPassword,
        name: 'ForgetPassword',
        meta: { title: 'Reset Password | RPM', guestOnly: true }
      }
    ],
  },
  {
    path: '/dashboard',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: Dashboard,
        name: 'Dashboard',
        meta: { title: 'Dashboard | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/question',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: Question,
        name: 'Question',
        meta: { title: 'Question | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/question/add',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: QuestionAdd,
        name: 'QuestionAdd',
        meta: { title: 'Add Question | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/question/:id/edit',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: QuestionEdit,
        name: 'QuestionEdit',
        meta: { title: 'Edit Question - RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/question/:id/show',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: QuestionShow,
        name: 'QuestionShow',
        meta: { title: 'Question - RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/test',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: Test,
        name: 'Test',        
        meta: { title: 'Test | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/test/add',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: TestAdd,
        name: 'TestAdd',
        meta: { title: 'Add Test | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/test/:id/edit',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: TestEdit,
        name: 'TestEdit',
        meta: { title: 'Edit Test | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/test/:id/preview',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: TestShow,
        name: 'TestShow',
        meta: { title: 'Preview Test - RPM' }
      }
    ],
  },
  {
    path: '/test/:id/question',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: TestQuestion,
        name: 'TestQuestion',
        meta: { title: 'Test Questions | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/test/:id/fields',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: TestUserField,
        name: 'TestUserField',
        meta: { title: 'Registration Fields for Candidate - RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/performance/add',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: PerformanceAdd,
        name: 'PerformanceAdd',
        meta: { title: 'Add Performance | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/performance/:id/show',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: PerformanceShow,
        name: 'PerformanceShow',
        meta: { title: 'History Performance | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/assign',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: AssignList,
        name: 'AssignList',
        meta: { title: 'Assign Candidate to Test | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/assign/add',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: AssignAdd,
        name: 'AssignAdd',
        meta: { title: 'Set Assign Candidate to Test | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/assign/:id/send',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: AssignSend,
        name: 'AssignSend',
        meta: { title: 'Mail Send to Candidates | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/assign/:id/share',
    component: DashboardLayout,
    children: [
      {
        path: '',
        component: AssignSendOne,
        name: 'AssignSendOne',
        meta: { title: 'Mail Send to Candidates | RPM', authOnly: true }
      }
    ],
  },
  {
    path: '/online-test/:id/',
    component: PublicLayout,
    children: [
      {
        path: '',
        component: OnlineTest,
        name: 'OnlineTest',
        meta: { title: 'Online Test | RPM' }
      }
    ],
  },
  { path: "*", component: PageNotFound }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

function isLoggedIn() {
  return localStorage.getItem("auth");
}

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.authOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (!isLoggedIn()) {
      next({
        path: "/login",
        query: { redirect: to.fullPath }
      });
    } else {
      next();
    }
  } else if (to.matched.some(record => record.meta.guestOnly)) {
    // this route requires auth, check if logged in
    // if not, redirect to login page.
    if (isLoggedIn()) {
      next({
        path: "/dashboard",
        query: { redirect: to.fullPath }
      });
    } else {
      next();
    }
  } else {
    next(); // make sure to always call next()!
  }
});

export default router;