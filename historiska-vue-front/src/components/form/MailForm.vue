<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from 'yup';
import {useUserStore} from "../../stores/useUserStore.ts";
import {SRV_STATUS} from "../../stores/requests.ts";
import useModalStore from "../../stores/useModalStore.ts";
import {ref} from "vue";
import LoadingSpinner from "../LoadingSpinner.vue";

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
let isLoading = ref<Boolean>(false);
async function submit(values) {
    isLoading.value = true;
    await authUser.updateUserAccount(JSON.stringify(values, null, 2), "account/update/email");
    isLoading.value = false;

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
    <Form @submit="submit" class="frm-modal small-container" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li v-if="unableUpdate && errorMessage" class="error-message">{{errorMessage}}</li>
            <li class="frm-item">
                <Field name="email" type="email" :placeholder="'Adresse e-mail'"
                       :class="{ 'frm-error-field': errors['email'] }" />
                <ErrorMessage name="email" class="frm-error-message" />
            </li>
            <li class="frm-item confirm-group-buttons btn-submit-loading">
                <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>