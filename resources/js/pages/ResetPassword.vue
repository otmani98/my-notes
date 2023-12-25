<script setup>
import { reactive, onMounted } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { useRouter, useRoute } from "vue-router";

const toast = useToast();
const router = useRouter();
const route = useRoute();

//redirect to home if token exist
onMounted(() => {
    if (
        localStorage.getItem("token") !== "undefined" &&
        localStorage.getItem("token")
    ) {
        router.push({ name: "home" });
    }
});

const data = reactive({
    email: "",
    password: "",
    password_confirmation: "",
});

const token = route.params.tokenData;
const email = route.query.email;
data.email = email;

const resetPassword = () => {
    if (!data.password || !data.password_confirmation) {
        return toast.error("you have to fill the fields");
    }
    if (data.password !== data.password_confirmation) {
        return toast.error("The password field confirmation does not match..");
    }
    axios
        .patch(`/api/resetPassword/${token}`, data)
        .then((res) => {
            if (res.data.status === "success") {
                localStorage.setItem("token", res.data.token);
                toast("login success");
                setTimeout(function () {
                    router.push({ name: "home" });
                }, 300);
            }
        })
        .catch((err) => {
            console.log(err);
            toast.error(err.response.data.message);
        });
};
</script>

<template>
    <div class="above-wrapper">
        <div class="wrapper">
            <h2>Reset Your Password</h2>
            <form @submit.prevent="(e) => {}">
                <div class="input-box">
                    <input
                        v-model="data.password"
                        type="password"
                        placeholder="Enter your new password"
                        required
                    />
                </div>
                <div class="input-box">
                    <input
                        v-model="data.password_confirmation"
                        type="password"
                        placeholder="Confirm your password"
                        required
                    />
                </div>
                <div class="input-box button">
                    <input
                        type="Submit"
                        value="Reset Password"
                        @click="resetPassword"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.above-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #4070f423;
}
.wrapper {
    position: relative;
    max-width: 430px;
    width: 100%;
    background: #fff;
    padding: 34px;
    border-radius: 6px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.wrapper h2 {
    position: relative;
    font-size: 22px;
    font-weight: 600;
    color: #333;
}
.wrapper h2::before {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    height: 3px;
    width: 28px;
    border-radius: 12px;
    background: #4070f4;
}
.wrapper form {
    margin-top: 30px;
}
.wrapper form .input-box {
    height: 52px;
    margin: 18px 0;
}
form .input-box input {
    height: 100%;
    width: 100%;
    outline: none;
    padding: 0 15px;
    font-size: 17px;
    font-weight: 400;
    color: #333;
    border: 1.5px solid #c7bebe;
    border-bottom-width: 2.5px;
    border-radius: 6px;
    transition: all 0.3s ease;
}
.input-box input:focus,
.input-box input:valid {
    border-color: #4070f4;
}
form .policy {
    display: flex;
    align-items: center;
}
form h3 {
    color: #707070;
    font-size: 14px;
    font-weight: 500;
    margin-left: 10px;
}
.input-box.button input {
    color: #fff;
    letter-spacing: 1px;
    border: none;
    background: #4070f4;
    cursor: pointer;
}
.input-box.button input:hover {
    background: #0e4bf1;
}
form .text h3 {
    color: #333;
    width: 100%;
    text-align: center;
}
form .text h3 a {
    color: #4070f4;
    text-decoration: none;
}
form .text h3 a:hover {
    text-decoration: underline;
}
</style>
