<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import {useUserStore} from "../../stores/useUserStore.ts";
import * as yup from "yup";

const authUser = useUserStore();

const emit = defineEmits<{
    accountActivateSuccess: void
}>();
function submit(value) {
    authUser.activateAccount(value);
    emit("accountActivateSuccess");
}

const schema = yup.object({
    activationCode: yup.string()
        .required("Veuillez saisir le code")
});
</script>

<template>
    <Form @submit="submit" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li class="frm-item">
                <Field name="activationCode" type="text" :placeholder="'Code d\'activation'"
                       :class="{ 'frm-error-field': errors['activationCode'] }" />
                <ErrorMessage name="activationCode" class="frm-error-message" />
            </li>
            <li class="frm-item">
                <button class="btn">Activer</button>
            </li>
        </ul>
    </Form>
</template>

<style scoped lang="scss">

</style>