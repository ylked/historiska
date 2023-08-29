<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from 'yup';

import {useUserStore} from "../../stores/useUserStore.ts";
const authUser = useUserStore();

const emit = defineEmits<{
    loginSuccess: void
}>()

// Form errors managements
const schema = yup.object({
    id: yup.string()
        .required("Veuillez saisir votre nom d'utilisateur ou adresse e-mail"),
    password: yup.string()
        .required("Veuillez saisir votre mot de passe")
        .min(8, "Il faut au moins 8 caractères"),
});

function submit(values) {

    authUser.login(JSON.stringify(values, null, 2));
    emit("loginSuccess");

}

</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li class="frm-item">
                <Field name="id" type="text" :placeholder="'Nom d\'utilisateur ou adresse e-mail'"
                       :class="{ 'frm-error-field': errors['id'] }" />
                <ErrorMessage name="id" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="password" type="password" :placeholder="'********'"
                       :class="{ 'frm-error-field': errors['password'] }" />
                <ErrorMessage name="password" class="frm-error-message" />
            </li>
            <li class="frm-item forget-password">
                <RouterLink :to="{ name: 'mot-de-passe-oublie' }"> Mot de passe oublié ?</RouterLink>
            </li>
            <li class="frm-item">
                <button class="btn">Connexion</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">
.forget-password
{
    text-align: right;
}
</style>