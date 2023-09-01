<script lang="ts">

import {defineComponent} from "vue";
import {useUserStore} from "../stores/useUserStore.ts";
import Decorator from "../components/Decorator.vue";
import Nav from "../components/Nav.vue";

export default defineComponent({
    components: {Nav, Decorator},
    data() {
        return {
            code: '',
            userStore: useUserStore()
        }
    },
    async mounted() {
        this.code = this.$route.params.code;
        await this.userStore.activateAccount(this.code);
    }
});

</script>

<template>
  <div>
      <Nav />
      <div class="content-container">
          <Decorator element="<h1>Activation compte</h1>" class="title"/>
          <p>Votre compte est activé</p>
          <RouterLink :to="{name:'Accueil'}"><button class="btn">Retour à l'accueil</button></RouterLink>
      </div>
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

</style>