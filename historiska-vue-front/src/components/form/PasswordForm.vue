<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";

const props = defineProps({
    forget: {
        type: Boolean,
        default: false
    },
});

const requiredMessage: string = "Veuillez remplir ce champ";
const schema = yup.object({
    newPassword: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        ),
        //.notOneOf([yup.ref('passwordOld')], 'Le mot passe doit être différent de votre ancien'),
    passwordConfirmation: yup.string()
        .required(requiredMessage)
        .oneOf([yup.ref('newPassword')!], 'Les mots de passe doivent être identiques')
});

const schemaWithForget = yup.object({
    passwordOld: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        ),
    newPassword: yup.string()
        .required(requiredMessage)
        .matches(
            /^(?=.*[A-Za-z])(?=.*\d)(?=.{8,})/,
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        )
        .notOneOf([yup.ref('passwordOld')], 'Le mot passe doit être différent de votre ancien'),
    passwordConfirmation: yup.string()
        .required(requiredMessage)
        .oneOf([yup.ref('newPassword')!], 'Les mots de passe doivent être identiques')
});

function submit(values) {
    // TODO SEND TO API

    if(props.forget) {
        delete values.passwordOld;
    }

    delete values.passwordConfirmation;
    console.log(JSON.stringify(values, null, 2));
}

</script>

<template>
    <Form @submit="submit" class="frm-modal" :validation-schema="forget ? schemaWithForget : schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li v-if="forget" class="frm-item">
                <Field name="passwordOld" type="password" :placeholder="'Ancien mot de passe'"
                       :class="{ 'frm-error-field': errors['passwordOld'] }" />
                <ErrorMessage name="passwordOld" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="newPassword" type="password" :placeholder="'Nouveau mot de passe'"
                       :class="{ 'frm-error-field': errors['newPassword'] }" />
                <ErrorMessage name="newPassword" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="passwordConfirmation" type="password" :placeholder="'Confirmation mot de passe'"
                       :class="{ 'frm-error-field': errors['passwordConfirmation'] }" />
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