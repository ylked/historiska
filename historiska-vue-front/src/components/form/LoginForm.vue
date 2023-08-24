<script lang="ts">
import InputComponent from "./InputComponent.vue";
import {defineComponent} from "vue";

export default defineComponent({
    components: {InputComponent},
    data()
    {
        return {
            username: '',
            password: '',
            usernameError: '',
            passwordError:'',
        }
    },
    methods: {
        handleSubmit() {
            // TODO handle with api username, mail and password
            this.usernameError = this.username.length !== 0 ? "" : "Ce nom d'utilisateur ou cette adresse e-mail incorrect";
            this.passwordError = this.password.length !== 0 ? "" : "Le nom d'utilisateur ou le mot de passe est incorrect";
        },
        getValue(value, id) {
            this.$data[id] = value;
        }
    }
});
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <ul class="frm-items">
            <InputComponent type="text" id="username" placeholder="Nom d'utilisateur ou adresse e-mail" required
                            :error-name="usernameError" @updateInputValue="getValue" />
            <InputComponent type="password" id="password" placeholder="Mot de passe" required
                            :error-name="passwordError" @updateInputValue="getValue" />
            <li class="forget-password">
                <RouterLink :to="{ name: 'mot-de-passe-oublie' }"> Mot de passe oublié ?</RouterLink>
            </li>
            <li class="frm-item">
                <button class="btn">Connexion</button>
            </li>
        </ul>
    </form>

    <div>
        Pas de compte ? <RouterLink :to="{ name: 'Inscription' }">Inscris-toi !</RouterLink>
    </div>
</template>

<style scoped lang="scss">
  .frm-items {
      list-style: none;
      padding: 0;
      li {
          text-align: center;
      }

      .forget-password
      {
          text-align: right;
      }
  }
</style>