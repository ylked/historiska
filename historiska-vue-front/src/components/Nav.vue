<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
    <header>
        <div class="container">
            <nav>
                <RouterLink :to="{ name : 'Accueil' }" class="logo-text">Historiska</RouterLink>
                <div class="main-nav">
                    <RouterLink :to="{ name : 'Collection' }">Collection</RouterLink>
                    <RouterLink :to="{ name : 'Recompense' }">Récompense</RouterLink>
                    <RouterLink :to="{ name : 'Entrer-code' }">Entrer-code</RouterLink>
                    <RouterLink :to="{ name : 'Connexion' }">Connexion</RouterLink>
                </div>
            </nav>
        </div>
    </header>
</template>

<style scoped lang="scss">
nav
{
    display: flex;
    justify-content: space-between;
    .logo-text
    {
        font-family: $title-font;
    }
}
</style>