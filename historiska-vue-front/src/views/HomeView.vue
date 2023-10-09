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
                    <RouterLink :to="{ name: 'Connexion' }" class="btn" v-if="!user.authUser.is_connected">Connexion</RouterLink>
                    <RouterLink :to="{ name: 'Inscription' }" class="btn" v-if="!user.authUser.is_connected">Inscription</RouterLink>
                    <RouterLink :to="{ name: 'Collection' }" class="btn" v-if="user.authUser.is_connected">Collection</RouterLink>
                    <RouterLink :to="{ name: 'Recompense' }" class="btn" v-if="user.authUser.is_connected">Récompense</RouterLink>
                    <a class="btn" href="https://discord.gg/Q8jtnYv9dE" v-if="user.authUser.is_connected" target="_blank">Discord</a>
                    <RouterLink :to="{ name: 'Deconnexion' }" class="btn" v-if="user.authUser.is_connected">Déconnexion</RouterLink>
                </nav>

                <div class="bottom">
                    <RouterLink :to="{ name: 'Entrer-code' }" class="btn" v-if="user.authUser.is_connected">Entrer code</RouterLink>
                    <p>© 2023 - Fleury Miranda, Dekhli Nima et Plumey Simon</p>
                </div>
            </div>
    </section>
</template> 

<style scoped lang="scss">
.home-section {
    padding: 50px 50px 10px;
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
    .bottom
    {
        display: flex;
        flex-direction: column;
        align-items: center;
        p
        {
            margin-top: 20px;
            margin-bottom: 0px;
        }
    }
}


@media (max-width: 768px) {
  .home-section {
    background: none;
    h1
    {
        font-size: 4em;
    }
    .volute
    {
        width: 14em;
    }
  }
}
</style>