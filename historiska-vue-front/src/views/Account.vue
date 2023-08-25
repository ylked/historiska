<script lang="ts">
  import Nav from "../components/Nav.vue";
  import Decorator from "../components/Decorator.vue";
  import Modal from "../components/Modal.vue";
  import {defineComponent} from "vue";
  import {useMeta} from "vue-meta";

  // Import store
  import useModalStore from "../stores/useModalStore";
  import UpdateUsernameForm from "../components/form/UpdateUsernameForm.vue";
  import UpdateMailForm from "../components/form/UpdateMailForm.vue";
  import UpdatePasswordForm from "../components/form/UpdatePasswordForm.vue";

  // Initialize store
  const store = useModalStore();

  export default defineComponent({
      components: {Nav, Modal, Decorator},
      setup() {
          useMeta({
              title: 'Profil',
              description: "La page profil permet à l'utilisateur de modifier les informations de son compte comme son" +
                  "nom d'utilisateur, son adresse e-mail ainsi que son mot de passe",
              htmlAttrs: { lang: 'fr', amp: true }
          })
      },
      methods:{
          openUpdateUsername() {
              store.openModal({
                  component: UpdateUsernameForm,
              });
          },
          openUpdateUserMail()
          {
              store.openModal({
                  component: UpdateMailForm,
              });
          },
          openUpdateUserPassword()
          {
              store.openModal({
                  component: UpdatePasswordForm,
              });
          }
      }
  });

</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <Decorator element="<h1>Profil</h1>" />

            <Modal></Modal>

            <ul class="list-items list-account-infos">
                <li class="list-item">
                    <input type="text" id="" value="username" disabled>
                    <button type="button" class="btn" @click="openUpdateUsername">Modifier</button>
                </li>
                <li class="list-item">
                    <input type="email" id="" value="exemple@exemple.com" disabled>
                    <button type="button" class="btn" @click="openUpdateUserMail">Modifier</button>
                </li>
                <li class="list-item">
                    <input type="password" id="" value="**********" disabled>
                    <button type="button" class="btn" @click="openUpdateUserPassword">Modifier</button>
                </li>
            </ul>
        </div>
    </section>
</template>

<style scoped lang="scss">
.content-container {
  display: flex;
  align-items: center;
  flex-direction: column;
}

.list-items {
  list-style: none;
  padding: 0;
}

.list-item {
  margin: 15px 0;
  display: flex;
  align-items: center;

  input {
    margin-right: 15px;
  }
}

</style>