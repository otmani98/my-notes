<script setup>
import NavBar from "../components/NavBar.vue";
import UpdateNote from "../components/UpdateNote.vue";
import DeleteNote from "../components/DeleteNote.vue";
import { reactive, ref } from "vue";
import axios from "axios";
import { useToast } from "vue-toastification";
import { useRouter, useRoute } from "vue-router";

const toast = useToast();
const router = useRouter();
const route = useRoute();

const theNote = reactive({
    title: "",
    brief: "",
    content: "",
    color: "",
    created_at: "",
    updated_at: "",
});

const showUpdateNote = ref(false);
const showDeleteNote = ref(false);

const getNote = async () => {
    try {
        let res = await axios.get(`/api/notes/${route.params.id}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });

        if (res.data.status === "success") {
            theNote.title = res.data.data.title;
            theNote.brief = res.data.data.brief;
            theNote.content = res.data.data.content;
            theNote.color = res.data.data.color;
            theNote.created_at = res.data.data.created_at;
            theNote.updated_at = res.data.data.updated_at;
        }
    } catch (error) {
        console.log(error.response.data);
    }
};
getNote();

const updateHere = (data) => {
    theNote.title = data.title;
    theNote.brief = data.brief;
    theNote.content = data.content;
    theNote.color = data.color;
    theNote.created_at = data.created_at;
    theNote.updated_at = data.updated_at;
};
</script>

<template>
    <NavBar />
    <UpdateNote
        :data="theNote"
        :id="route.params.id"
        :show-update-note="showUpdateNote"
        @close-update-note="showUpdateNote = false"
        @success-update-note="updateHere"
    />
    <DeleteNote
        :id="route.params.id"
        :show-delete-note="showDeleteNote"
        @close-delete-note="showDeleteNote = false"
    />
    <div class="container">
        <div class="tools">
            <div class="tool" @click="router.push({ name: 'home' })">
                <img src="../media/icons8-back-64.png" alt="" />
            </div>
            <div class="note-tools">
                <div class="tool" @click="showUpdateNote = true">
                    <img src="../media/icons8-edit-64.png" alt="" />
                </div>
                <div class="tool" @click="showDeleteNote = true">
                    <img src="../media/icons8-delete-64.png" alt="" />
                </div>
                <div class="tool">
                    <img src="../media/icons8-print-50.png" alt="" />
                </div>
            </div>
        </div>
        <div class="note" :style="{ backgroundColor: theNote.color }">
            <div class="content">
                <h3>{{ theNote.title }}</h3>
                <p>
                    {{ theNote.content }}
                </p>
            </div>
            <div class="date">
                <span
                    >Updated at:
                    {{ new Date(theNote.updated_at).toLocaleString() }}</span
                >
                <span
                    >Created at:
                    {{ new Date(theNote.created_at).toLocaleString() }}</span
                >
            </div>
        </div>
    </div>
</template>

<style scoped>
.tools {
    width: 80%;
    display: flex;
    justify-content: space-between;
    margin: 25px auto;
}

.note-tools {
    display: flex;
}

.tool {
    height: 50px;
    width: 50px;
    cursor: pointer;
}

.tool img {
    width: 100%;
    height: 100%;
}

.note {
    width: 80%;
    background-color: #0e4bf165;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    margin: 0 auto;
    margin-bottom: 15px;
    text-align: center;
}

.note h3 {
    margin-bottom: 25px;
}

.note p {
    margin: auto;
    margin-bottom: 35px;
    width: 80%;
}

.date {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 10px;
}
</style>
