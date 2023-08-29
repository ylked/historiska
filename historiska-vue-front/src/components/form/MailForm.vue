<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from 'yup';

const schema = yup.object({
    email: yup.string()
        .required("Veuillez remplir ce champ")
        .email("Veuillez saisir une adresse valide"),
});
function submit(values) {
    // TODO SEND TO API
    console.log(JSON.stringify(values, null, 2));
}

</script>

<template>
    <Form @submit="submit" class="frm-modal" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li class="frm-item">
                <Field name="email" type="email" :placeholder="'Adresse e-mail'"
                       :class="{ 'frm-error-field': errors['email'] }" />
                <ErrorMessage name="email" class="frm-error-message" />
            </li>
            <li class="frm-item confirm-group-buttons">
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>