<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import {useUserStore} from "../../stores/useUserStore.ts";
import * as yup from "yup";
import {SRV_STATUS} from "../../stores/requests.ts";
import {reactive} from "vue";

const authUser = useUserStore();

const emit = defineEmits<{
    accountActivateSuccess: void
}>();
async function submit(value) {
    await authUser.activateAccount(value.activationCode);
    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        emit("accountActivateSuccess");
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

async function resendCode() {
    await authUser.resendActivateAccount();
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

function countdown(secondes: number) {
    store.isCountdown = true;
    let secondsRemaining = secondes; // 2 minutes en secondes

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

const schema = yup.object({
    activationCode: yup.string()
        .required("Veuillez saisir le code")
});
</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }">
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
            <li class="frm-item confirm-group-buttons">
                <button class="btn">Activer</button>
                <button class="btn" type="button" @click="resendCode" :disabled="store.btnDisable"
                        :class="{'disable': store.btnDisable}">Renvoyer code</button>
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