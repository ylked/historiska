<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";

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
        .oneOf([yup.ref('password')], 'Les mots de passe doivent être identiques')
});

function submit(values) {
    // TODO SEND TO API
    delete values.passwordConfirmation;
    console.log(JSON.stringify(values, null, 2));
}
</script>

<template>
    <Form @submit="submit" class="frm-modal" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
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
            <li class="frm-item confirm-group-buttons">
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>