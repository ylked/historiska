<script lang="ts">
import {defineComponent} from "vue";
import {useUserStore} from "../stores/useUserStore.ts";
import Decorator from "../components/Decorator.vue";
import Nav from "../components/Nav.vue";
import {useMeta} from "vue-meta";
import {SRV_STATUS} from "../stores/requests.ts";

export default defineComponent({
    setup() {
        useMeta({
            title: 'Activation du compte',
            description: "La page activation du compte permet à l'utilisateur d'activer compte utilisateur.",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    components: {Nav, Decorator},
    data() {
        return {
            code: '',
            userStore: useUserStore(),
            message: ''
        }
    },
    async mounted() {
        this.code = this.$route.params.code;
        await this.userStore.activateAccount(this.code);
        if(this.userStore.data.status === SRV_STATUS.NOT_FOUND) {
            this.message = "Ce code est invalide.";
        } else {
            this.message = "Votre compte est activé";
        }
    }
});
</script>

<template>
  <div>
      <Nav />
      <div class="content-container">
          <Decorator subPath element="<h1>Activation compte</h1>" class="title"/>
          <p>{{ message }}</p>
          <RouterLink :to="{name:'Accueil'}"><button class="btn">Retour à l'accueil</button></RouterLink>
      </div>
  </div>
</template>

<style scoped lang="scss">
.content-container {
  display: flex;
  align-items: center;
  flex-direction: column;

  p {
    margin-bottom: 40px;
  }

  .title
  {
    margin-top: 70px;
    margin-bottom: 25px;
  }
}
</style>