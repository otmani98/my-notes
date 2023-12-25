<script setup>
import axios from "axios";
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";

const router = useRouter();
const toast = useToast();

const getFirstName = async () => {
    const res = await axios.get("/api/user", {
        headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
    });
    data.firstName = res.data.firstName;
};

const data = reactive({
    firstName: "",
    logoutText: "Logout",
});

//get firstName add it to state firstName
getFirstName();

const logout = async () => {
    data.logoutText = "Logging out..";
    axios
        .post(
            "/api/logout",
            {},
            {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            }
        )
        .then((response) => {
            localStorage.clear();
            router.push({ name: "login" });
            toast("logout success");
        })
        .catch((error) => {
            toast.error(error.response.data.message);
        });
};
</script>

<template>
    <nav>
        <div class="container">
            <h1><a href="/" class="base">Notes</a></h1>
            <ul>
                <li>{{ data.firstName }}</li>
                <li>
                    <button @click.prevent="logout">
                        {{ data.logoutText }}
                    </button>
                </li>
            </ul>
        </div>
    </nav>
</template>

<style scoped>
nav {
    width: 100%;
    color: white;
    background-color: var(--main-color);
    /* box-shadow: rgba(255, 255, 255, 0.1) 0px 1px 1px 0px inset,
        rgba(50, 50, 93, 0.25) 0px 50px 100px -20px,
        rgba(0, 0, 0, 0.3) 0px 30px 60px -30px; */
}

nav .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav h1 a {
    font-family: "Indie Flower", cursive;
    color: white;
    font-size: 50px;
}

nav ul {
    display: flex;
}

nav ul li {
    padding: 6px;
    font-size: 15px;
}

nav ul li button {
    border: none;
    background-color: transparent;
    outline: none;
    font-size: 15px;
    font-weight: bold;
    color: white;
    cursor: pointer;
}
</style>
