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
    await authUser.activateAccount(value);
    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        emit("accountActivateSuccess");
    }
}

const store = reactive({
    timestamp: 0,
    delayDisable: 2*60*1000,
    btnDisable: false,
    sendEmail: false,
    isError: false
});

// TODO A tester avec un compte
async function resendCode() {
    if(store.timestamp === 0) {
        store.btnDisable = true;
        store.timestamp = Date.now();
        await authUser.resendActivateAccount();
        if(authUser.data.status === SRV_STATUS.SUCCESS) {
            store.sendEmail = true;
        } else {
            store.isError = true;
        }
    } else {
        if((Date.now() - store.timestamp) < store.delayDisable) {
            store.btnDisable = true;
        } else {
            store.btnDisable = false;
            store.timestamp = 0;
        }
    }
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
                <p v-if="store.sendEmail">Un nouveau code d'activation a été envoyé à votre adresse e-mail.</p>
                <p v-if="store.isError">Un problème est survenu, veuillez contacter l'administrateur</p>
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