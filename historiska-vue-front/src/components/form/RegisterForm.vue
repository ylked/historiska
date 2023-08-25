<script lang="ts">

import InputComponent from "./InputComponent.vue";

import { defineComponent } from 'vue'
export default defineComponent({
    components: {InputComponent},
    data()
    {
        return {
            username: '',
            usermail: '',
            password: '',
            confirmPassword: '',
            usernameError: '',
            usermailError: '',
            passwordError:'',
            confirmPasswordError: '',
        }
    },
    methods: {
        getValue(value, id) {
            this.$data[id] = value;
        },
        handleSubmit() {
            // TODO handle with api username, mail and password
            this.usernameError = this.username.length !== 0 ? "" : "Ce nom d'utilisateur déjà existant";
            this.usermailError = this.usermail.length > 5 ? "" : "Ce nom d'utilisateur déjà existant";
            this.passwordError = this.password.length < 5 ? "Le mot de passe est trop court (min. 5 caractères)" : "";

            if(this.confirmPassword !== this.password)
            {
                this.confirmPasswordError = "Les mots de passe ne correspondent pas";
            }
        }
    }
})

</script>

<template>
    <form @submit.prevent="handleSubmit">
        <ul class="frm-items">
            <InputComponent type="text" id="username" required placeholder="Nom d'utilisateur"
                            :error-name="usernameError" @updateInputValue="getValue" />
            <InputComponent type="email" id="usermail" placeholder="Adresse e-mail" required
                            :error-name="usermailError" @updateInputValue="getValue"/>
            <InputComponent type="password" id="password" required placeholder="Mot de passe"
                            :error-name="passwordError" @updateInputValue="getValue" />
            <InputComponent type="password" id="confirmPassword" placeholder="Confirmation du mot de passe"
                            required :error-name="confirmPasswordError" @updateInputValue="getValue" />
            <li class="frm-item">
                <button class="btn">Inscription</button>
            </li>
        </ul>
    </form>

    <div>
        Déjà un compte ? <RouterLink :to="{ name: 'Connexion' }">Connecte-toi !</RouterLink>
    </div>
</template>

<style scoped lang="scss">

</style>