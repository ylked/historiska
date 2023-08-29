<script setup lang="ts">

import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";

const schema = yup.object({
    username: yup.string()
        .required("Veuillez remplir ce champ")
});
function submit(values) {
    // TODO SEND TO API
    console.log(JSON.stringify(values, null, 2));
}

</script>

<template>
    <Form class="frm-modal" @submit="submit" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li class="frm-item">
                <Field name="username" type="text" :placeholder="'Nom d\'utilisateur'"
                       :class="{ 'frm-error-field': errors['username'] }" />
                <ErrorMessage name="username" class="frm-error-message" />
            </li>
            <li class="frm-item confirm-group-buttons">
                <button class="btn">Appliquer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>