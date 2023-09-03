<script lang="ts">
    import Nav from "../components/Nav.vue";
    import Decorator from "../components/Decorator.vue";
    import LoginForm from "../components/form/LoginForm.vue";
    import {defineComponent} from "vue";

    // Import and use store
    import {useUserStore} from "../stores/useUserStore.ts";
    import {useMeta} from "vue-meta";
    import ForgetPasswordForm from "../components/form/ForgetPasswordForm.vue";
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
                type: Boolean,
                required: false
            },
            forget: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        data() {
          return {
              isLogout: false
          }
        },
        components: {ForgetPasswordForm, Decorator, LoginForm, Nav},
        methods: {
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
            <div v-if="this.$props.forget" class="content-container">
                <Decorator element="<h1>Mot de passe oublié</h1>" class="title"/>
                <p>Un email de récupération de votre mot de passe va vous être envoyé.</p>
                <ForgetPasswordForm />
            </div>
            <div class="content-container" v-else>
                <Decorator element="<h1>Connexion</h1>" class="title"/>
                <p v-if="isLogout" class="error-message">Vous avez bien été déconnecté</p>
                <LoginForm />
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