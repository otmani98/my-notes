import "./bootstrap";
import "../css/app.css";
import { createApp } from "vue";
import router from "./router";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import App from "./App.vue";

const app = createApp({});

app.use(router);

app.use(Toast, { timeout: 2500, position: "bottom-left" });

app.component("app-component", App);

app.mount("#app");
