<script setup lang="ts">
import {Form, Field} from "vee-validate";
import {SRV_STATUS} from "../../stores/requests.ts";
import {useUserStore} from "../../stores/useUserStore.ts";
import {ref} from "vue";
import LoadingSpinner from "../LoadingSpinner.vue";

const authUser = useUserStore();

let isLoading = ref<Boolean>(false);
let message:string = '';
async function submit(values) {
    isLoading.value = true;
    await authUser.forgotPassword(JSON.stringify(values, null, 2));
    isLoading.value = false;

    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        message = "Un email a été envoyé à votre adresse email";
    } else {
        message = "Impossible d'envoyer l'email. Vérifier que votre nom d'utilisateur ou votre adresse email sont corrects.";
    }
}
</script>

<template>
    <Form @submit="submit" class="small-container">
        <ul class="frm-items">
            <li>{{message}}</li>
            <li class="frm-item">
                <Field name="id" type="text" :placeholder="'Nom d\'utilisateur ou adresse e-mail'"/>
            </li>
            <li class="frm-item btn-submit-loading">
                <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
                <button class="btn">Envoyer mail</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>