<script lang="ts">
import {defineComponent} from "vue";
import {useMeta} from "vue-meta";
import Nav from "../components/Nav.vue";
import Decorator from "../components/Decorator.vue";
import LoginForm from "../components/form/LoginForm.vue";
import EnterCodeForm from "../components/form/EnterCodeForm.vue";
import InfoBox from "../components/InfoBox.vue";

export default defineComponent({
    components: {InfoBox, EnterCodeForm, LoginForm, Decorator, Nav},
    setup() {
      useMeta({
          title: 'Récupération code',
          description: "La page récupération code de transfert est permet de récupérer une carte via le code de transfert entrer",
          htmlAttrs: { lang: 'fr', amp: true }
      })
    }
});

</script>

<template>
  <Nav />
    <section class="content-container">
        <Decorator class="title" element="<h1>Entrer code</h1>"/>
        <EnterCodeForm />
        <div class="info-box">
            <InfoBox title="Informations" text="Demander à vos amis de vous fournir les codes des cartes qu'ils ont en
        double. Ensuite vous entrez le code dans le champ ci-contre pour récupérer la carte dans votre collection.
        <br> Attention, uniquement 1 carte peut-être récupérée par jour."/>
        </div>
    </section>
</template>

<style scoped lang="scss">
  .content-container {
    display: flex;
    align-items: center;
    flex-direction: column;
    height: 90vh;

    .title {
      padding-top: 70px;
    }

    .info-box {
      position: absolute;
      right: 30px;
      top: 23%;
    }
  }
</style>