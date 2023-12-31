<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import {useUserStore} from "../../stores/useUserStore.ts";
import * as yup from "yup";
import {SRV_STATUS} from "../../stores/requests.ts";
import {reactive, ref} from "vue";
import router from "../../router";
import LoadingSpinner from "../LoadingSpinner.vue";

let isLoading = ref<Boolean>(false);
const authUser = useUserStore();

// Activate account
async function submit(value) {
    isLoading.value = true;
    await authUser.activateAccount(value.activationCode);
    isLoading.value = false;

    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        await router.push({name: "Compte"});
    } else {
        store.isError = true;
        store.errorMessage = "Code de vérification invalide";
    }
}

const store = reactive({
    btnDisable: false,
    sendEmail: false,
    isError: false,
    errorMessage: '',
    isCountdown: false,
    countDownMessage: '',
    countDownTime: 120
});

// Ask for resend an activation email
async function resendCode() {
    isLoading.value = true;
    await authUser.resendActivateAccount();
    isLoading.value = false;

    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        store.sendEmail = true;
        store.errorMessage = "Un nouveau code d'activation a été envoyé à votre adresse e-mail.";
    } else if(authUser.data.status === SRV_STATUS.TOO_MANY_REQUESTS) {
        store.isError = true;
        store.errorMessage = "Le dernier email a été envoyé il y a moins de 2 minutes. Vérifier dans vos emails.";
    } else {
        store.isError = true;
        store.errorMessage = "Le compte est déjà actif";
    }

    store.btnDisable = true;
    countdown(store.countDownTime);
}

// Countdown to know how minutes remaining before
// be enable to ask again new activation email
function countdown(secondes: number) {
    store.isCountdown = true;
    let secondsRemaining = secondes; // 2 minutes in secondes

    const countdownInterval = setInterval(() => {
        const minutes = Math.floor(secondsRemaining / 60);
        const seconds = secondsRemaining % 60;

        store.countDownMessage = `${minutes} minute(s) et ${seconds} seconde(s) restante(s)`;
        secondsRemaining--;

        if (secondsRemaining < 0) {
            clearInterval(countdownInterval);
            store.btnDisable = false;
            store.isCountdown = false;
        }
    }, 1000);
}

// Form validation
const schema = yup.object({
    activationCode: yup.string()
        .required("Veuillez saisir le code")
});
</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }" class="small-container">
        <ul class="frm-items">
            <li>
                <p v-if="store.sendEmail">{{store.errorMessage}}</p>
                <p v-if="store.isError">Un problème est survenu, veuillez contacter l'administrateur</p>
                <p v-if="store.isCountdown">{{store.countDownMessage}} avant la possibilité de renvoyer un mail d'activation.</p>
            </li>
            <li class="frm-item">
                <Field name="activationCode" type="text" :placeholder="'Code d\'activation'"
                       :class="{ 'frm-error-field': errors['activationCode'] }" />
                <ErrorMessage name="activationCode" class="frm-error-message" />
            </li>
            <li class="frm-item btn-submit-loading">
                <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
                <div class="confirm-group-buttons">
                    <button class="btn">Activer</button>
                    <button class="btn" type="button" @click="resendCode" :disabled="store.btnDisable"
                            :class="{'disable': store.btnDisable}">Renvoyer code</button>
                </div>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">
.confirm-group-buttons {
    justify-content: space-evenly;
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