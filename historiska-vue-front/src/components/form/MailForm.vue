<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from 'yup';
import {useUserStore} from "../../stores/useUserStore.ts";
import {SRV_STATUS} from "../../stores/requests.ts";
import useModalStore from "../../stores/useModalStore.ts";

const authUser = useUserStore();
const modalStore = useModalStore();

const schema = yup.object({
    email: yup.string()
        .required("Veuillez remplir ce champ")
        .email("Veuillez saisir une adresse valide"),
});

const emit = defineEmits<{
    updateSuccess: void
}>();

let unableUpdate: boolean = false;
let errorMessage: string = '';
async function submit(values) {
    await authUser.updateUserAccount(JSON.stringify(values, null, 2), "account/update/email");
    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        modalStore.closeModal();
        unableUpdate = false;
    } else {
        unableUpdate = true;
        errorMessage = "L'adresse email est invalide ou déjà associé à un compte.";
    }
}

</script>

<template>
    <Form @submit="submit" class="frm-modal" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li v-if="unableUpdate && errorMessage" class="error-message">{{errorMessage}}</li>
            <li class="frm-item">
                <Field name="email" type="email" :placeholder="'Adresse e-mail'"
                       :class="{ 'frm-error-field': errors['email'] }" />
                <ErrorMessage name="email" class="frm-error-message" />
            </li>
            <li class="frm-item confirm-group-buttons">
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>