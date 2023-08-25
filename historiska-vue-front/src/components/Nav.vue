<script setup lang="ts">
import { RouterLink } from 'vue-router'
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