<script lang="ts">
import {defineComponent} from "vue";
import Decorator from "../components/Decorator.vue";
import ForgetPasswordForm from "../components/form/ForgetPasswordForm.vue";
import PasswordForm from "../components/form/PasswordForm.vue";
import Nav from "../components/Nav.vue";
import {useMeta} from "vue-meta";

export default defineComponent({
    setup() {
        useMeta({
            title: 'Récupération du compte',
            description: "La page récupération du compte permet à l'utilisateur de créer un nouveau mot de passe pour son" +
                "compte utilisateur, après l'avoir oublié.",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    data() {
        return {
            token: ''
        }
    },
    components: {Nav, PasswordForm, ForgetPasswordForm, Decorator},
    mounted() {
        this.token = this.$route.params.token;
    }
});
</script>

<template>
    <div>
        <Nav />
        <section>
            <div class="content-container">
                <Decorator subPath element="<h1>Nouveau mot de passe</h1>" class="title"/>
                <PasswordForm forget :token="token" />
            </div>
        </section>
    </div>
</template>

<style scoped lang="scss">
.content-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 90vh;

    .title {
        margin-top: 70px;
        margin-bottom: 25px;
    }
}
</style>