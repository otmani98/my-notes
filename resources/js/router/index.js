import { createRouter, createWebHistory } from "vue-router";
import axios from "axios";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: () => import("../pages/Home.vue"),
        },
        {
            path: "/notes/:id",
            name: "note",
            component: () => import("../pages/Note.vue"),
        },
        {
            path: "/login",
            name: "login",
            component: () => import("../pages/Login.vue"),
        },
        {
            path: "/register",
            name: "register",
            component: () => import("../pages/Register.vue"),
        },
        {
            path: "/forgotPassword",
            name: "forgotPassword",
            component: () => import("../pages/ForgotPassword.vue"),
        },
        {
            path: "/resetPassword/:tokenData",
            name: "resetPassword",
            component: () => import("../pages/ResetPassword.vue"),
        },
    ],
});

router.beforeEach((to, from) => {
    if (to.name === "login") {
        return true;
    }

    if (to.name === "register") {
        return true;
    }

    if (to.name === "forgotPassword") {
        return true;
    }

    if (to.name === "resetPassword") {
        return true;
    }

    if (!localStorage.getItem("token")) {
        return {
            name: "login",
        };
    }

    checkTokenAuthenticity();
});

const checkTokenAuthenticity = () => {
    axios
        .get("/api/user", {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        })
        .then((response) => {})
        .catch((error) => {
            localStorage.removeItem("token");
            router.push({ name: "login" });
        });
};

export default router;
