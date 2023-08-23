<script lang="ts">

export default {
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
            this.usernameError = this.username.length !== 0 ? "" : "Ce nom d'utilisateur ou cette adresse e-mail n'existe pas";
            this.passwordError = this.password.length !== 0 ? "" : "Le nom d'utilisateur ou le mot de passe est incorrect";
        },
    }
}

</script>

<template>
    <form @submit.prevent="handleSubmit">
        <ul class="frm-items">
            <li class="frm-item">
                <input type="text" id="username" placeholder="Nom d'utilisateur ou adresse e-mail"
                       v-model="username" :class="{'frm-error-field' : usernameError }" autocomplete="off" autofocus>
                <div class="frm-error-message" v-if="usernameError">{{ usernameError }}</div>
            </li>

            <li class="frm-item">
                <input type="password" id="password" placeholder="Mot de passe" v-model="password"
                       :class="{'frm-error-field' : passwordError }">
                <div class="frm-error-message" v-if="passwordError">{{ passwordError }}</div>
            </li>
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
  input {
      width: 100%;
      padding: 10px;
      outline: none;
      border: solid 3px black;
      box-shadow: none;
      border-radius: 30px;
  }

  .frm-items {
      list-style: none;
      padding: 0;
      li {
          margin: 30px 0;
          text-align: center;
      }

      .forget-password
      {
          text-align: right;
      }
  }

  form {
      min-width: 30%;
      max-width: 60%;
  }

  .frm-error-message
  {
      margin: 10px 0 0 15px;
      color: darkred;
      text-align: left;
      font-weight: bold;
  }

  .frm-error-field
  {
      border-color: darkred;
  }
</style>