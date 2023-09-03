<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import {SRV_STATUS} from "../../stores/requests.ts";
import * as yup from 'yup';
import router from "../../router";

// Import and use store
import {useUserStore} from "../../stores/useUserStore.ts";
const user = useUserStore();

// Form errors managements
const schema = yup.object({
    id: yup.string()
        .required("Veuillez saisir votre nom d'utilisateur ou adresse e-mail"),
    password: yup.string()
        .required("Veuillez saisir votre mot de passe")
        .min(8, "Il faut au moins 8 caractères"),
});

// Connection
let unableConnect = false;
async function submit(values) {
    await user.login(JSON.stringify(values, null, 2));
    if(user.data.status === SRV_STATUS.SUCCESS && user.getToken) {
        unableConnect = false;
        await router.push({name: 'Collection'});
    } else {
        unableConnect = true;
    }
}

</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li v-if="unableConnect" class="error-message">
                Connexion impossible: identifiant ou mot de passe incorrect
            </li>
            <li class="frm-item">
                <Field name="id" type="text" :placeholder="'Nom d\'utilisateur ou adresse e-mail'"
                       :class="{ 'frm-error-field': errors['id'] || unableConnect }" :autocomplete="false"/>
                <ErrorMessage name="id" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="password" type="password" :placeholder="'********'"
                       :class="{ 'frm-error-field': errors['password'] || unableConnect }" :autocomplete="true"/>
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