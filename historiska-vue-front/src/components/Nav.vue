<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { useUserStore } from "../stores/useUserStore.ts";
import { onMounted, ref } from "vue";

const user = useUserStore();

let collapsedNav = ref<boolean>(false);

const toggleNav = () => {
    collapsedNav.value = !collapsedNav.value;
    document.documentElement.style.overflow = collapsedNav.value ? 'hidden' : 'auto';
}

onMounted(async () => {
    document.documentElement.style.overflow = 'auto';
    await user.getToken;
    await user.getAuthUser;
});

</script>

<template>
    <div class="info" v-if="!user.authUser.is_verified && user.getToken">
        <p>
            Votre compte n'est pas encore activé, ce qui restreint certaines fonctionnalités. <br>
            Consultez votre e-mail
            pour le code d'activation et cliquez sur le lien correspondant pour activer votre compte. <br>
            Vous pouvez également utiliser le lien ci-dessous :
            <RouterLink :to="{ name: 'compte-activation' }">Activation du compte.</RouterLink>
        </p>
    </div>

    <header>
        <div class="container" :class="{ 'collapsed': collapsedNav }">
            <nav>
                <RouterLink :to="{ name: 'Accueil' }" class="historika-text">Historiska</RouterLink>
                <div class="main-nav">
                    <RouterLink v-if="user.getToken" :to="{ name: 'Collection' }" class="nav-btn">Collection</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Recompense' }" class="nav-btn">Récompense</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Entrer-code' }" class="nav-btn">Entrer-code</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Compte' }" class="nav-btn">Compte</RouterLink>
                    <RouterLink v-if="!user.getToken" :to="{ name: 'Connexion' }" class="nav-btn">Connexion</RouterLink>
                    <RouterLink v-if="!user.getToken" :to="{ name: 'Inscription' }" class="nav-btn">Inscription</RouterLink>
                    <RouterLink v-if="user.getToken" :to="{ name: 'Deconnexion' }" class="nav-btn">Déconnexion</RouterLink>
                    <button class="collapsedBtn" @click="toggleNav"><svg v-if="collapsedNav"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="30px" height="30px">
                            <path
                                d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z" />
                        </svg>
                        <svg v-else="collapsedNav" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="30px"
                            height="30px">
                            <path
                                d="M 0 9 L 0 11 L 50 11 L 50 9 Z M 0 24 L 0 26 L 50 26 L 50 24 Z M 0 39 L 0 41 L 50 41 L 50 39 Z" />
                        </svg>
                    </button>
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

        button {
            display: none;
        }

        .nav-btn {
            padding: 5px 10px;
            color: black;

            &:hover {
                text-decoration: underline;
            }
        }
    }
}

.collapsedBtn {
    padding: 5px;
    background: none;
    border: none;
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

@media (max-width: 768px) {
    nav {
        .main-nav {
            .nav-btn {
                display: none;
            }

            button {

                display: inline-block;
            }
        }
    }

    .container.collapsed {
        position: absolute;
        padding: 0;
        z-index: 999;

        nav {
            padding-top: 50px;
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow: hidden;
            background-color: white;

            .main-nav {
                margin-top: 75px;
                display: grid;
                z-index: 999;

                .nav-btn {
                    text-align: center;
                    display: block;
                }

                button {
                    margin-top: 30px;
                }
            }
        }
    }



}
</style>