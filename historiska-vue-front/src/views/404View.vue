<script setup lang="ts">
import Nav from "../components/Nav.vue";
</script>

<template>
    <div>
        <Nav />
        <div class="content container">
            <h1>404 not found</h1>
            <p>Il semblerait que vous vous soyez perdu dans l'histoire...</p>
        </div>
    </div>
</template> 

<style scoped lang="scss">
.content {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    height: 80vh;

    h1 {
        font-size: 6em;
    }
}
</style>