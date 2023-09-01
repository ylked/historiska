<script lang="ts">
import { useMeta } from 'vue-meta'
import {defineComponent} from "vue";
import {useUserStore} from "../stores/useUserStore.ts";

export default defineComponent({
    setup() {
        useMeta({
            title: 'Accueil',
            description: "Historiska est une application de collection de cartes en rapport avec des personnages historiques",
            htmlAttrs: { lang: 'fr', amp: true }
        });

        const user = useUserStore();
        return {
            user
        }
    }
});
</script>

<template>
    <section class="home-section">
            <div class="content-container">
                <div class="title">
                    <h1>Historiska</h1>
                    <img src="../assets/volute.svg" alt="" class="volute">
                </div>
                <nav class="home-nav">
                    <RouterLink :to="{ name: 'Collection' }" class="btn">Collection</RouterLink>
                    <RouterLink :to="{ name: 'Recompense' }" class="btn">Récompense</RouterLink>
                    <RouterLink :to="{ name: 'Connexion' }" class="btn" v-if="!user.authUser.is_connected">Connexion</RouterLink>
                    <RouterLink :to="{ name: 'Deconnexion' }" class="btn" v-if="user.authUser.is_connected">Déconnexion</RouterLink>
                </nav>
                
                <RouterLink :to="{ name: 'Entrer-code' }" class="btn">Entrer code</RouterLink>
            </div>
    </section>
</template> 

<style scoped lang="scss">
.home-section {
    padding: 50px;
    display: flex;
    justify-content: center;
    height: 100vh;
    background-image: url("../assets/abraham-lincoln.png"), url("../assets/jules-cesar.png");
    background-position: -100px, calc(100% + 100px);
    background-size: 75vh, 60vh;
    background-repeat: no-repeat;
    h1
    {
        margin: 0;
        line-height: 0.8;
        font-size: 5em;
    }
    .volute
    {
        width: 17em;
    }
    .content-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-direction: column;

        .home-nav {
            display: flex;
            flex-direction: column;
            margin-bottom: 90px;
            .btn
            {
                text-align: center;
                margin: 10px 0;
            }
        }
    }
}
</style>