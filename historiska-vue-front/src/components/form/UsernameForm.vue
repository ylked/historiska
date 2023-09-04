<script setup lang="ts">

import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";
import {useUserStore} from "../../stores/useUserStore.ts";
import {SRV_STATUS} from "../../stores/requests.ts";
import useModalStore from "../../stores/useModalStore.ts";
import LoadingSpinner from "../LoadingSpinner.vue";
import {ref} from "vue";

const authUser = useUserStore();
const modaltStore = useModalStore();

// Form validation
const schema = yup.object({
    username: yup.string()
        .required("Veuillez remplir ce champ")
});

// Submit form
let unableUpdate: boolean = false;
let errorMessage: string = '';
let isLoading = ref<Boolean>(false);
async function submit(values) {
    isLoading.value = true;
    await authUser.updateUserAccount(JSON.stringify(values, null, 2), "account/update/username");
    isLoading.value = false;

    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        unableUpdate = false;
        modaltStore.closeModal();
    } else {
        unableUpdate = true;
        if(authUser.data.status === SRV_STATUS.BAD_REQUEST) {
            errorMessage = "Le nom contient des caractères interdits ou le nom est déjà associé à un compte";
        } else {
            errorMessage = "Le compte doit d'abord être activé pour changer le nom d'utilisateur"
        }
    }
}
</script>

<template>
    <Form class="frm-modal small-container" @submit="submit" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li class="frm-item">
                <Field name="username" type="text" :placeholder="'Nom d\'utilisateur'"
                       :class="{ 'frm-error-field': errors['username'] }" />
                <ErrorMessage name="username" class="frm-error-message" />
            </li>
            <li class="frm-item btn-submit-loading">
                <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>