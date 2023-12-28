<template>
    <div class="container">
        <h1>404 Not Found</h1>
        <router-link class="link" to="/">Go back to home</router-link>
    </div>
</template>

<style scoped>
.container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}
h1 {
    color: var(--main-color);
}
.link {
    color: var(--main2-color);
}
</style>
