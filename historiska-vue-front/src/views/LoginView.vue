<script setup lang="ts">
    import Nav from "../components/Nav.vue";
    import Decorator from "../components/Decorator.vue";
    import LoginForm from "../components/form/LoginForm.vue";
</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <Decorator element="<h1>Connexion</h1>"/>
            <LoginForm />

            <div>
                <p>Pas de compte ? <RouterLink :to="{ name: 'Inscription' }">Inscris-toi !</RouterLink></p>
            </div>
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