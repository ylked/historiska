<script lang="ts">
  import Nav from "../components/Nav.vue";
  import Decorator from "../components/Decorator.vue";
  import Modal from "../components/Modal.vue";
  import {defineComponent} from "vue";
  import {useMeta} from "vue-meta";

  // Import store
  import useModalStore from "../stores/useModalStore";
  import UpdateForm from "../components/form/UpdateForm.vue";
  import AccountActivateForm from "../components/form/AccountActivateForm.vue";
  import InfoBox from "../components/InfoBox.vue";
  import {useUserStore} from "../stores/useUserStore.ts";

  // Initialize store
  const store = useModalStore();
  const user = useUserStore();

  export default defineComponent({
      props:{
          accountActivation: {
              type: Boolean,
              default: false
          },
          title: {
              type: String,
              required: true
          }
      },
      components: {InfoBox, AccountActivateForm, Nav, Modal, Decorator},
      setup() {
          useMeta({
              title: 'Profil',
              description: "La page profil permet à l'utilisateur de modifier les informations de son compte comme son" +
                  "nom d'utilisateur, son adresse e-mail ainsi que son mot de passe",
              htmlAttrs: { lang: 'fr', amp: true }
          })
          const user = useUserStore();
          return {
              user
          }
      },
      methods:{
          openUpdateUsername() {
              store.openModal({
                  component: UpdateForm,
                  props: {
                      title: "Modifier",
                      text: "Veuillez entrer votre nouveau nom d'utilisateur",
                      componentName: "UsernameForm"
                  }
              });
          },
          openUpdateUserMail() {
              store.openModal({
                  component: UpdateForm,
                  props: {
                      title: "Modifier",
                      text: "Veuillez entrer votre notre nouvelle adresse e-mail.",
                      componentName: "MailForm"
                  }
              });
          },
          openUpdateUserPassword() {
              store.openModal({
                  component: UpdateForm,
                  props: {
                      title: "Modifier",
                      text: "Veuillez entrer votre nouveau mot de passe.",
                      componentName: "PasswordForm"
                  }
              });
          },
      }
  });

</script>

<template>
    <div>
        <Nav />
        <section>
            <div class="content-container">
                <Decorator :element="'<h1>' + title + '</h1>'" class="title" />

                <AccountActivateForm v-if="accountActivation" />
                <div class="info-box" v-if="accountActivation">
                    <InfoBox title="Information"
                             text="Un code d'activation a été envoyé par mail. Vérifier dans votre boîte mail, ensuite copier
                         le code et coller-le dans le champ ci-après." />
                </div>

                <Modal v-if="!accountActivation"></Modal>

                <ul class="list-items list-account-infos" v-if="!accountActivation">
                    <li class="list-item">
                        <input type="text" disabled :value="user.authUser['username']">
                        <button type="button" class="btn" @click="openUpdateUsername"
                                :class="{'disable' : !user.authUser['is_verified']}"
                                :disabled="user.authUser['is_verified'] === false">Modifier</button>
                    </li>
                    <li class="list-item">
                        <input type="email" disabled :value="user.authUser['email']">
                        <button type="button" class="btn" @click="openUpdateUserMail">Modifier</button>
                    </li>
                    <li class="list-item">
                        <input type="text" id="" value="**********" disabled>
                        <button type="button" class="btn" @click="openUpdateUserPassword"
                                :class="{'disable' : !user.authUser['is_verified']}"
                                :disabled="user.authUser['is_verified'] === false">Modifier</button>
                    </li>
                </ul>
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

.info-box {
    position: absolute;
    right: 30px;
    top: 23%;
}

.disable {
    opacity: 0.5;
    cursor: no-drop;
    transform: none;
    box-shadow: none;
    &:hover {
        transition: none;
    }
}

</style>