<script lang="ts">
import {defineComponent} from "vue";
import {useMeta} from "vue-meta";
import Nav from "../components/Nav.vue";
import Decorator from "../components/Decorator.vue";
import LoginForm from "../components/form/LoginForm.vue";
import EnterCodeForm from "../components/form/EnterCodeForm.vue";

export default defineComponent({
    components: {EnterCodeForm, LoginForm, Decorator, Nav},
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
  }
</style>