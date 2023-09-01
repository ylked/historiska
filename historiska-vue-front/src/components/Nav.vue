<script setup lang="ts">
import { RouterLink } from 'vue-router'
import {useUserStore} from "../stores/useUserStore.ts";
import {onMounted} from "vue";

const user = useUserStore();
let isValidToken: boolean = false;

onMounted(async () => {
    await user.getToken;
    await user.getAuthUser;
});

</script>

<template>
    <div class="info" v-if="!user.authUser.is_verified && user.getToken">
        <p>
            Votre compte n'est pas activé. Certaines fonctionnalités ne sont pas disponibles. <br>
            Pour activer votre compte, vous pouvez cliquer sur le lien suivant :
            <RouterLink :to="{name: 'compte-activation'}">Activation du compte.</RouterLink>
        </p>
    </div>

    <header>
        <div class="container">
            <nav>
                <RouterLink :to="{ name: 'Accueil' }" class="historika-text">Historiska</RouterLink>
                <div class="main-nav">
                    <RouterLink v-if="user.getToken" :to="{ name: 'Collection' }" class="nav-btn">Collection</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Recompense' }" class="nav-btn">Récompense</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Entrer-code' }" class="nav-btn">Entrer-code</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Compte' }" class="nav-btn">Compte</RouterLink>
                    <RouterLink v-if="!user.getToken" :to="{ name: 'Connexion' }" class="nav-btn">Connexion</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Deconnexion' }" class="nav-btn">Déconnexion</RouterLink>
                </div>
            </nav>
        </div>
    </header>
</template>

<style scoped lang="scss">
nav {
    height: 10vh;
    display: flex;
    justify-content: space-between;
    .historika-text {
        font-family: $title-font;
        font-size: 2em;
        color: black;
        display: flex;
        align-items: center;
    }

    .main-nav {
        display: flex;
        align-items: center;

        .nav-btn {
            padding: 5px 10px;
            color: black;

            &:hover {
                text-decoration: underline;
            }
        }
    }
}

.info {
    background-color: $beige;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: space-around;
    p {
        margin: 0;
    }
}
</style>