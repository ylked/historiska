<script lang="ts">
import {defineComponent} from "vue";
import InputComponent from "./Input.vue";
import Decorator from "../Decorator.vue";

export default defineComponent({
    components: {Decorator, InputComponent},
    props:{
        forget: Boolean,
    },
    data(){
        return {
            formData:{
                token: String,
                password: String,
            },
            oldPassword:'',
            password: '',
            confirmPassword: '',
            passwordError: '',
            confirmPasswordError: '',
            passwordLength: 8,
            action:'',
            showAlertBox: false,
            alertBoxMessage:"AlertBox",
            noError: true,
        }
    },
    methods:{
        handleSubmit(){
            // Manage errors
            this.handleErrors();

            // Send data
            if(this.noError)
            {
                // TODO test with api
                console.log("SENDING REQUEST");
                //this.sendRequest();
            }
        },
        getValue(value, id) {
            this.$data[id] = value;
        },
        handleErrors(){
            this.noError = true;
            /*
            * Pour la regex :
            * ^: La vérification se fait dès le début de la chaîne de caractère
            * (?.*=A-Z) : Vérifie la présence d'au moins une majuscule comprise entre A et Z
            * .* => N'importe quel caractère même répété
            * (?=.*\d) : N'importe quel nombre d'au moins 1 chiffre
            * */
            if(this.password.length < this.passwordLength ||
                !/^(?=.*[A-Z])(?=.*\d)/.test(this.password)) {
                console.log("Mot de passe:", this.password.length);
                console.log("Regex test:", /^(?=.*[A-Z])(?=.*\d)/.test(this.password));
                this.passwordError = "Le mot de passe de satisfait pas les conditions suivantes : \n Minimum 8" +
                    "caractères, doit contenir au moins un chiffre et une majuscule";
                this.noError = false;
            } else {
                this.passwordError = "";
            }

            if(this.confirmPassword !== this.password)
            {
                this.confirmPasswordError = "Les mots de passes sont différents";
                this.noError = false;
            } else {
                this.confirmPasswordError = "";
            }
        },
        sendRequest(){
            // Config request
            const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.formData),
            };

            // Choose the right root
            if(this.forget)
            {
                this.action = "/recover";
            } else {
                this.action = "/account/update/password";
            }

            // Send data
            fetch(this.action, requestOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Unable to login request.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle response
                    if(data.status !== 200)
                    {
                        this.showAlertBox = true;
                        this.alertBoxMessage = data.message;
                    }
                })
                .catch(error => {
                    // Handle errors
                    console.error(error);
                });
        },
    }
});
</script>

<template>
    <Transition duration="1000" name="nested">
        <div v-if="showAlertBox" class="alert-box inner">{{ alertBoxMessage }}</div>
    </Transition>

    <div class="container-update-modal">
        <Decorator element="<h1>Modifier</h1>" />
        <p>Veuillez entrer votre nouveau mot de passe.</p>
        <form class="frm-modal" @submit.prevent="handleSubmit">
            <ul class="frm-items">
                <InputComponent v-if="forget" type="password" id="oldPassword" placeholder="Ancien mot de passe"
                                required :error-name="oldPassword" @updateInputValue="getValue" />
                <InputComponent type="password" id="password" placeholder="Nouveau mot de passe" required
                                :error-name="passwordError" @updateInputValue="getValue"/>
                <InputComponent type="password" id="confirmPassword" placeholder="Confirmer mot de passe"
                                required :error-name="confirmPasswordError" @updateInputValue="getValue"/>
                <li class="frm-item confirm-group-buttons">
                    <button class="btn">Appliquer</button>
                </li>
            </ul>
        </form>
    </div>
</template>

<style scoped lang="scss">

.alert-box {
  background: $beige;
  border-bottom: solid 5px $dark-beige;
  border-radius: 30px;
  width: 25%;
  color: black;
  padding: 20px;
  text-align: center;
  margin: 0 auto;
  z-index: 9999;
  position: absolute;
  top: 10px;
  left: 0;
  right: 0;
}

.nested-enter-active, .nested-leave-active {
  transition: all 0.3s ease-in-out;
}
.nested-leave-active {
  transition-delay: 0.25s;
}

.nested-enter-from,
.nested-leave-to {
  transform: translateX(30px);
  opacity: 0;
}
</style>