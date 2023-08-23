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
        handleSubmit()
        {
            // TODO gestion des erreurs
            console.log("Form submitted !");
        }
    }
}

</script>

<template>
    <form @submit.prevent="handleSubmit">
        <ul>
            <li class="frm-item">
                <input type="text" id="username" placeholder="Nom d'utilisateur ou adresse e-mail" autofocus autocomplete="off">
                <div class="frm-error-message" v-if="usernameError">{{ usernameError }}</div>
            </li>

            <li class="frm-item">
                <input type="password" id="password" placeholder="Mot de passe">
                <div class="frm-error-message" v-if="passwordError">{{ passwordError }}</div>
            </li>
            <li class="forget-password">
                <!-- TODO à changer pour un routerlink-->
                <a href="">Mot de passe oublié ?</a>
            </li>
            <li class="frm-item">
                <button>Connexion</button>
            </li>
        </ul>
    </form>
</template>

<style scoped lang="scss">
  input, button {
      border-radius: 30px;
  }

  button {
      margin: 0 auto;
      padding: 10px 20px;
      border: none;
      box-shadow: $dark-purple 5px 5px 1px;
      background: $purple;
      color: white;
  }

  button:hover
  {
      cursor: pointer;
  }

  input {
      width: 100%;
      padding: 10px;
      outline: none;
      border: solid 3px black;
      box-shadow: none;
  }

  ul {
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
</style>