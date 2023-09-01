<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";
import {useUserStore} from "../../stores/useUserStore.ts";
import {SRV_STATUS} from "../../stores/requests.ts";
import useModalStore from "../../stores/useModalStore.ts";

const authUser = useUserStore();
const modalStore = useModalStore();

const props = defineProps({
    forget: {
        type: Boolean,
        default: false
    },
});

const requiredMessage: string = "Veuillez remplir ce champ";
const schema = yup.object({
    password: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        ),
    passwordConfirmation: yup.string()
        .required(requiredMessage)
        .oneOf([yup.ref('password')!], 'Les mots de passe doivent être identiques')
});

const schemaWithForget = yup.object({
    passwordOld: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        ),
    password: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        )
        .notOneOf([yup.ref('passwordOld')], 'Le mot passe doit être différent de votre ancien'),
    passwordConfirmation: yup.string()
        .required(requiredMessage)
        .oneOf([yup.ref('password')!], 'Les mots de passe doivent être identiques')
});

let unableUpdate:boolean = false;
let errorMessage: string = '';
async function submit(values) {
    let url = "account/update/password";
    delete values.passwordConfirmation;

    if(props.forget) {
        delete values.passwordOld;
        url = "forget";
        values.id = authUser.getAuthUser["username"];
        await authUser.updateUserAccount(JSON.stringify(values, null, 2), url);
    } else {
        await authUser.updateUserAccount(JSON.stringify(values, null, 2), url);
    }

    if(authUser.data.status === SRV_STATUS.SUCCESS) {
        unableUpdate = false;
        modalStore.closeModal();
    } else {
        unableUpdate = true;
        errorMessage = "Le compte doit être activé, le mot de passe doit être différent du précédent et ne pas contenir" +
            " de caractères interdits";
    }
}

</script>

<template>
    <Form @submit="submit" class="frm-modal" :validation-schema="forget ? schemaWithForget : schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li v-if="unableUpdate && errorMessage" class="error-message">{{errorMessage}}</li>
            <li v-if="forget" class="frm-item">
                <Field name="passwordOld" type="password" :placeholder="'Ancien mot de passe'"
                       :class="{ 'frm-error-field': errors['passwordOld'] }" />
                <ErrorMessage name="passwordOld" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="password" type="password" :placeholder="'Nouveau mot de passe'"
                       :class="{ 'frm-error-field': errors['password'] }" :autocomplete="true" />
                <ErrorMessage name="password" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="passwordConfirmation" type="password" :placeholder="'Confirmation mot de passe'"
                       :class="{ 'frm-error-field': errors['passwordConfirmation'] }" :autocomplete="true" />
                <ErrorMessage name="passwordConfirmation" class="frm-error-message" />
            </li>
            <li class="frm-item confirm-group-buttons">
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>