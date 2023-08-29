<script setup lang="ts">
import { RouterLink } from 'vue-router'
import {useUserStore} from "../stores/useUserStore.ts";

import {onMounted} from "vue";

/*const authStore = useUserStore();

onMounted(async () => {
   await authStore.getUser();
   console.log(authStore.getUser());
});*/

</script>

<template>
    <header>
        <div class="container">
            <nav>
                <RouterLink :to="{ name: 'Accueil' }" class="historika-text">Historiska</RouterLink>
                <div class="main-nav">
                    <RouterLink :to="{ name: 'Collection' }" class="nav-btn">Collection</RouterLink>
                    <RouterLink :to="{ name: 'Recompense' }" class="nav-btn">Récompense</RouterLink>
                    <RouterLink :to="{ name: 'Entrer-code' }" class="nav-btn">Entrer-code</RouterLink>
                    <RouterLink :to="{ name: 'Compte' }" class="nav-btn">Compte</RouterLink>
                    <RouterLink :to="{ name: 'Connexion' }" class="nav-btn">Connexion</RouterLink>
                    <RouterLink :to="{ name: 'Deconnexion' }" class="nav-btn">Déconnexion</RouterLink>
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
</style>