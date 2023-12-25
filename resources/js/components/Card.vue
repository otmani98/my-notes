<script setup>
import { watch, ref } from "vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import { useRouter } from "vue-router";

const { cardData } = defineProps(["cardData"]);

const toast = useToast();
const router = useRouter();
</script>

<template>
    <div
        class="card"
        @click="router.push(`/notes/${cardData.id}`)"
        :style="{ backgroundColor: cardData.color }"
    >
        <div class="content">
            <h3>{{ cardData.title }}</h3>
            <p>
                {{ cardData.brief }}
            </p>
        </div>
        <div class="date">
            {{ new Date(cardData.created_at).toLocaleString() }}
        </div>
    </div>
</template>

<style scoped>
.card {
    width: 250px;
    background-color: #0e4bf165;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 10px;
    border-radius: 15px;
    height: 300px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    overflow: hidden;
}

.card h3 {
    margin-bottom: 15px;
}
</style>
