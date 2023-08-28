<script lang="ts">
    import Nav from "../components/Nav.vue";
    import Decorator from "../components/Decorator.vue";
    import LoginForm from "../components/form/LoginForm.vue";
    import {defineComponent} from "vue";

    export default defineComponent({
        components: {Decorator, LoginForm, Nav}
    })
</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <Decorator element="<h1>Connexion</h1>" class="title"/>
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

        .title
        {
            margin-top: 70px;
            margin-bottom: 25px;
        }
    }
</style>