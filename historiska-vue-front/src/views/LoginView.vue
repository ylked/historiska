<script setup lang="ts">
    import LoginForm from "../components/form/LoginForm.vue";
    import Nav from "../components/Nav.vue";
</script>

<template>
    <section>
        <div class="content-container">
            <Nav />
            <div class="decoration-title">
                <img src="../assets/ornement.svg" alt="Ornement" class="decoration-left">
                <h1>Connexion</h1>
                <img src="../assets/ornement.svg" alt="Ornement" class="decoration-right">
            </div>
            <LoginForm />
        </div>
    </section>
</template>

<style scoped lang="scss">
    .content-container {
        display: flex;
        align-items: center;
        flex-direction: column;
    }
</style>