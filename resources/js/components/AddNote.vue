<script setup>
import { watch, ref } from "vue";
import { useToast } from "vue-toastification";
import axios from "axios";
const emit = defineEmits(["closeAddNote", "successAddNote"]);
const { showAddNote } = defineProps(["showAddNote"]);

const toast = useToast();

const getRandomColor = () => {
    let letters = "BCDEF".split("");
    let color = "#";
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * letters.length)];
    }
    return color;
};

const title = ref("");
const brief = ref("");
const content = ref("");

watch(content, () => {
    brief.value = content.value.slice(0, 85) + "...";
});

const addNote = async () => {
    if (!title.value || !content.value) {
        return toast.error("you have to fill al the fields");
    }
    try {
        const data = {
            title: title.value,
            brief: brief.value,
            color: getRandomColor(),
            content: content.value,
        };
        const res = await axios.post("/api/notes", data, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });
        if (res.data.status === "success") {
            toast("the note has been added successfully");
            emit("closeAddNote");
            emit("successAddNote", res.data.data);
        }
    } catch (error) {
        // toast.error(error.response.data.message);
    }
};
</script>

<template>
    <div v-if="showAddNote" class="overlay">
        <div class="wrapper">
            <h2>Create new note</h2>
            <form @submit.prevent="(e) => {}">
                <div class="input-box">
                    <input
                        type="text"
                        placeholder="Enter your title here..."
                        required
                        v-model.trim="title"
                    />
                </div>
                <div class="input-box textarea-box">
                    <textarea
                        rows="6"
                        placeholder="write your note here"
                        required
                        v-model.trim="content"
                    ></textarea>
                </div>
                <div class="input-box button">
                    <input type="Submit" value="Create" @click="addNote" />
                </div>
                <div class="input-box button">
                    <input
                        type="Submit"
                        value="Close"
                        @click="$emit('closeAddNote')"
                    />
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* overlay  + add note popup*/

.overlay {
    height: 100%;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.185);
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
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

.wrapper form .textarea-box {
    height: 200px;
}
form .input-box input,
.input-box textarea {
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
.input-box textarea:focus,
.input-box textarea:valid,
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
.input-box.button input,
.input-box.button textarea {
    color: #fff;
    letter-spacing: 1px;
    border: none;
    background: #4070f4;
    cursor: pointer;
}
.input-box.button input:hover,
.input-box.button textarea:hover {
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
