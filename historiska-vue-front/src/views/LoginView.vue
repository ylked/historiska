<script lang="ts">
    import Nav from "../components/Nav.vue";
    import Decorator from "../components/Decorator.vue";
    import LoginForm from "../components/form/LoginForm.vue";
    import {defineComponent} from "vue";
    import {boolean} from "yup";

    // Import and use store
    import {useUserStore} from "../stores/useUserStore.ts";
    import {useMeta} from "vue-meta";
    const authUser = useUserStore();
    export default defineComponent({
        setup() {
            useMeta({
                title: 'Connexion',
                description: "La page connexion permet à l'utilisateur de se connecter à son compte utilisateur",
                htmlAttrs: { lang: 'fr', amp: true }
            })
        },
        props: {
            logout: {
                type: boolean,
                required: false
            }
        },
        data() {
          return {
              isLogout: false
          }
        },
        components: {Decorator, LoginForm, Nav},
        methods: {
            redirectAfterLogin() {
                this.$router.push({name: 'Collection'});
            },
            async logOut() {
                await authUser.logout();
                if(!authUser.getToken) {
                    this.isLogout = true;
                    this.$router.push({name: 'Connexion'});
                    this.hideLogOutMessage();
                }
            },
            hideLogOutMessage() {
                setTimeout(() => {
                    this.isLogout = false;
                }, 5000)
            }
        },
        async mounted() {
            if (this.$props.logout) {
                await this.logOut();
            }
        }
    })
</script>

<template>
    <div>
        <Nav />
        <section>
            <div class="content-container">
                <Decorator element="<h1>Connexion</h1>" class="title"/>
                <p v-if="isLogout" class="error-message">Vous avez bien été déconnecté</p>
                <LoginForm v-on:login-success="redirectAfterLogin"/>
                <div>
                    <p>Pas de compte ? <RouterLink :to="{ name: 'Inscription' }">Inscris-toi !</RouterLink></p>
                </div>
            </div>
        </section>
    </div>
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

    p {
        margin-bottom: 30px;
    }

    .error-message {
        font-weight: bold;
        color: darkgreen;
        margin-bottom: 0;
    }
</style>