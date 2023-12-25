<script setup>
import NavBar from "../components/NavBar.vue";
import AddNote from "../components/AddNote.vue";
import Card from "../components/Card.vue";
import { ref } from "vue";
import axios from "axios";

const cardsOfNotes = ref([]);
const currentPage = ref(1);
const lastPage = ref(1);
const showAddNote = ref(false);

const getNotes = async () => {
    try {
        let res = await axios.get(`/api/notes?page=${currentPage.value}`, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
        });

        if (res.data.status === "success") {
            for (const note of res.data.data.data) {
                cardsOfNotes.value.push(note);
            }
            lastPage.value = res.data.data.last_page;
            //first page
            //last page
        }
    } catch (error) {
        console.log(error.response.data);
    }
};
getNotes();

const AddtoCards = (data) => {
    cardsOfNotes.value.unshift(data);
};

//when reach bottom fetch more data
window.onscroll = function () {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        if (lastPage.value > currentPage.value) {
            currentPage.value++;
            getNotes();
        }
    }
};
</script>

<template>
    <NavBar />
    <AddNote
        :showAddNote="showAddNote"
        @close-add-note="showAddNote = !showAddNote"
        @success-add-note="AddtoCards"
    />
    <div class="addPart">
        <div class="container">
            <div class="add" @click="showAddNote = showAddNote = true">
                <img src="../media/icons8-add-50.png" alt="" />
            </div>
        </div>
    </div>
    <div class="cards">
        <div class="container">
            <Card v-for="card in cardsOfNotes" :cardData="card" />
        </div>
    </div>
</template>

<style scoped>
.test {
    position: fixed;
    top: 50%;
    left: 50%;
}
.addPart .container {
    margin-top: 10px;
    display: flex;

    justify-content: end;
}

.add {
    height: 50px;
    width: 50px;
    cursor: pointer;
}

/* cards */
.cards {
    padding-bottom: 30px;
}

.cards .container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    flex-direction: row;
}
</style>
