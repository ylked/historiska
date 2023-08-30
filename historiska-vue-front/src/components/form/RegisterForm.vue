<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from 'yup';
import {request, SRV_STATUS} from "../../stores/requests.ts";
import {useUserStore} from "../../stores/useUserStore.ts";

const authUser = useUserStore();

const emit = defineEmits<{
    registerSuccess: void
}>();

const requiredMessage: string = "Veuillez remplir ce champ";
const schema = yup.object({
    username: yup.string()
        .required(requiredMessage)
        /* TODO Resolve problem
        .test('username-availability', 'Ce nom d\'utilisateur est déjà pris', async function(value) {
            if (value) {
                const isAvailable = await checkData(`availability/username/${value}`, "is_available");

                // true if available, false else
                return isAvailable;
            }
        })
        .test('username-validity', 'Ce nom d\'utilisateur est invalide', async function(value) {
            if (value) {
                const isAvailable = await checkData(`availability/username/${value}`, "is_valid");

                // true if available, false else
                return isAvailable;
            }
        })*/,
    email: yup.string()
        .required(requiredMessage)
        .email("Veuillez saisir une adresse valide")
        /* TODO Resolve problem
        .test('email-availability', 'Cette adresse email est déjà associée à un compte', async function(value) {
            if (value) {
                const isAvailable = await checkData(`availability/email/${value}`, "is_available");
                // true if available, false else
                return isAvailable;
            }
        })
        .test('email-validity', 'Cette adresse email n\'est pas valide', async function(value) {
            if (value) {
                const isAvailable = await checkData(`availability/email/${value}`, "is_valid");
                // true if available, false else
                return isAvailable;
            }
        })*/,
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

function submit(values) {
    delete values.passwordConfirmation;
    authUser.register(JSON.stringify(values, null, 2));
    emit("registerSuccess");
}

/* TODO Resolve problem
const checkData = async (url: string, property: string) => {
    try {
        const data = await request("get", url, "", "", "");
        if (data.status === SRV_STATUS.SUCCESS) {
            console.log("Validity : " + data.content.is_valid);
            console.log("Availability : " + data.content.is_available);
            console.log(data.content[property]);

            if(data.content[property]) {
                return true;
            } else {
                return "Cette valeur est invalide ou déjà utilisée";
            }
        }
    } catch (error) {
        console.error("Error : " + error);
    }
};*/

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