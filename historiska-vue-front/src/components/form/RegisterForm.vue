<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from 'yup';

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
            "Doit contenir 8 caracters, une majuscule ou une minuscule et un nombre"
        ),
    passwordConfirmation: yup.string()
        .required(requiredMessage)
        .oneOf([yup.ref('password')], 'Les mots de passe doivent être identiques')
});
function submit(values) {
    console.log(JSON.stringify(values, null, 2));
}
</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li class="frm-item">
                <Field name="username" type="text" :placeholder="'Nom d\'utilisateur'"
                       :class="{ 'frm-error-field': errors['username'] }" />
                <ErrorMessage name="username" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="email" type="text" :placeholder="'Adresse e-mail'"
                       :class="{ 'frm-error-field': errors['email'] }" />
                <ErrorMessage name="email" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="password" type="password" :placeholder="'********'"
                       :class="{ 'frm-error-field': errors['password'] }" />
                <ErrorMessage name="password" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <Field name="passwordConfirmation" type="password" :placeholder="'********'"
                       :class="{ 'frm-error-field': errors['passwordConfirmation'] }" />
                <ErrorMessage name="passwordConfirmation" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <button class="btn">Inscription</button>
            </li>
        </ul>
    </Form>
</template>