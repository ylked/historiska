<script setup lang="ts">
import {ErrorMessage, Field, Form} from "vee-validate";
import * as yup from 'yup';
import {request, SRV_STATUS} from "../../stores/requests.ts";
import router from "../../router";

// Import and use userStore
import {useUserStore} from "../../stores/useUserStore.ts";
import {ref} from "vue";
import LoadingSpinner from "../LoadingSpinner.vue";
const authUser = useUserStore();

// Form errors management
const requiredMessage: string = "Veuillez remplir ce champ";
const schema = yup.object({
    username: yup.string()
        .required(requiredMessage),
    email: yup.string()
        .required(requiredMessage)
        .email("Veuillez saisir une adresse valide"),
    password: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caractères, une majuscule ou une minuscule et un nombre"
        ),
    passwordConfirmation: yup.string()
        .required(requiredMessage)
        .oneOf([yup.ref('password')!], 'Les mots de passe doivent être identiques')
});

const checkData = async (url: string, property: string) => {
    try {
        const data = await request("get", url, "", "", "");
        if (data.status === SRV_STATUS.SUCCESS) {
            return data.content[property] === true;
        }
    } catch (error) {
        console.error("Error : " + error);
    }
};


// Register
let frmErrors = [];
let unableRegister:boolean = false;
let usernameError = false;
let usermailError = false;
let isLoading = ref<Boolean>(false);
async function submit(values) {
    delete values.passwordConfirmation;

    // TODO Workaround but not ideal
    frmErrors = [];
    unableRegister = false;
    usernameError = false;
    usermailError = false;
    if(!await checkData(`availability/username/${values.username}`, "is_available")) {
        frmErrors.push("Le nom d'utilisateur est déjà existant");
        unableRegister = true;
        usernameError = true;
    }

    if(!await checkData(`availability/username/${values.username}`, "is_valid")) {
        frmErrors.push("Le nom d'utilisateur est invalide");
        unableRegister = true;
        usernameError = true;
    }

    if(!await checkData(`availability/email/${values.email}`, "is_available")) {
        frmErrors.push("L'adresse email est déjà utilisée");
        unableRegister = true;
        usermailError = true;
    }

    if(!await checkData(`availability/email/${values.email}`, "is_valid")) {
        frmErrors.push("L'adresse email est invalide");
        unableRegister = true;
        usermailError = true;
    }

    if(!unableRegister) {
        isLoading.value = true;
        await authUser.register(JSON.stringify(values, null, 2));
        isLoading.value = false;

        if(authUser.data.status === SRV_STATUS.SUCCESS) {
            await router.push({name: 'Collection'});
        } else {
            unableRegister = true;
        }
    }
}
</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }" class="small-container">
        <ul class="frm-items">
            <li class="error-message" v-if="unableRegister">
                <ul class="frm-availibity-error frm-items">
                    <li>Inscription impossible</li>
                    <li v-for="error in frmErrors">
                        {{error}}
                    </li>
                </ul>
            </li>
            <li class="frm-item">
                <Field name="username" type="text" :placeholder="'Nom d\'utilisateur'"
                       :class="{ 'frm-error-field': errors['username'] || usernameError }" />
                <ErrorMessage name="username" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="email" type="text" :placeholder="'Adresse e-mail'"
                       :class="{ 'frm-error-field': errors['email'] || usermailError }" />
                <ErrorMessage name="email" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="password" type="password" :placeholder="'********'"
                       :class="{ 'frm-error-field': errors['password'] }" :autocomplete="true"/>
                <ErrorMessage name="password" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="passwordConfirmation" type="password" :placeholder="'********'"
                       :class="{ 'frm-error-field': errors['passwordConfirmation'] }" :autocomplete="true" />
                <ErrorMessage name="passwordConfirmation" class="frm-error-message" />
            </li>
            <li class="frm-item btn-submit-loading">
                <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
                <button class="btn">Inscription</button>
            </li>
        </ul>
    </Form>
</template>

<style lang="scss">
.frm-availibity-error {
    text-align: left;
}
</style>