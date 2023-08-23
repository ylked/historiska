<script setup lang="ts">
    import LoginForm from "../components/form/LoginForm.vue";
</script>

<template>
    <div class="container">
        <main>
            <div class="decoration-title">
                <img src="../assets/ornement.svg" alt="Ornement" class="decoration-left">
                <h1>Connexion</h1>
                <img src="../assets/ornement.svg" alt="Ornement" class="decoration-right">
            </div>

            <LoginForm />
        </main>
    </div>
</template>

<style scoped lang="scss">
    main {
        display: flex;
        align-items: center;
        flex-direction: column;
    }
</style>