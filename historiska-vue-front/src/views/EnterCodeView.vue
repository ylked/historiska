<script lang="ts">
import { defineComponent } from "vue";
import { useMeta } from "vue-meta";
import Nav from "../components/Nav.vue";
import Decorator from "../components/Decorator.vue";
import LoginForm from "../components/form/LoginForm.vue";
import EnterCodeForm from "../components/form/EnterCodeForm.vue";
import InfoBox from "../components/InfoBox.vue";
import Modal from "../components/Modal.vue";
import {useUserStore} from "../stores/useUserStore.ts";

export default defineComponent({
    components: { InfoBox, EnterCodeForm, LoginForm, Decorator, Nav, Modal },
    setup() {
        useMeta({
            title: 'Récupération code',
            description: "La page récupération code de transfert est permet de récupérer une carte via le code de transfert entrer",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    data() {
        return {
            userStore: useUserStore(),
            userAccountActiv: false
        }
    },
    mounted() {
        this.userAccountActiv = this.userStore.getAuthUser.is_verified;
    },
});

</script>

<template>
    <div>
        <Nav />
        <section class="content-container">
            <Modal></Modal>
            <Decorator class="title" element="<h1>Entrer code</h1>" />
            <p v-if="!this.userAccountActiv">Activer votre compte pour avoir accès à cette fonctionnalité !</p>
            <EnterCodeForm v-else />
            <div class="info-box">
                <InfoBox title="Informations" text="Demander à vos amis de vous fournir les codes des cartes qu'ils ont en
        double. Ensuite vous entrez le code dans le champ ci-contre pour récupérer la carte dans votre collection.
        <br> Attention, uniquement 1 carte peut-être récupérée par jour." />
            </div>
        </section>
    </div>
</template>

<style scoped lang="scss">
.content-container {
    display: flex;
    align-items: center;
    flex-direction: column;
    height: 90vh;

    .title {
        margin-top: 70px;
        margin-bottom: 25px;
    }

    .info-box {
        position: absolute;
        right: 30px;
        top: 23%;
    }
}
</style>