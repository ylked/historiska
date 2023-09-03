<script setup lang="ts">
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";
import {useUserStore} from "../../stores/useUserStore.ts";
import {SRV_STATUS} from "../../stores/requests.ts";
import useModalStore from "../../stores/useModalStore.ts";
import router from "../../router";

const authUser = useUserStore();
const modalStore = useModalStore();

const props = defineProps({
    forget: {
        type: Boolean,
        default: false
    },
    token: {
        type: String,
        default: '',
    }
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

function redirect(componentName: string) {
    router.push({name: componentName});
}

let unableUpdate:boolean = false;
let errorMessage: string = '';
async function submit(values) {
    let url = "account/update/password";
    delete values.passwordConfirmation;

    if(props.forget) {
        values.token = props.token;
        await authUser.recoveryPassword(JSON.stringify(values, null, 2));
        console.log(authUser.data);
        if(authUser.data.status === SRV_STATUS.SUCCESS) {
            unableUpdate = false;
            errorMessage = "Mot de passe changé ! Vous allez être redirigé vers la page de connexion !";
            setTimeout( () => redirect('Connexion'), 2000);
        } else {
            unableUpdate = true;
            if(authUser.data.status === SRV_STATUS.FORBIDDEN) {
                errorMessage = "Une erreur s'est produite : Le nouveau mot de passe contient des caractères interdits" +
                    " ou le mot de passe est trop faible ou le mot de passe est identique au précédent.";
            } else {
                errorMessage = "Le code de récupération a expiré (validité : 15 minutes)." +
                    "Vous allez être redirigé vers la page 'Mot de passe oublié' afin de recommencer l'opération.";
                setTimeout( () => redirect('mot-de-passe-oublie'), 2000);
            }
        }
    } else {
        await authUser.updateUserAccount(JSON.stringify(values, null, 2), url);
        if(authUser.data.status === SRV_STATUS.SUCCESS) {
            unableUpdate = false;
            modalStore.closeModal();
        } else {
            unableUpdate = true;
            errorMessage = "Le compte doit être activé, le mot de passe doit être différent du précédent et ne pas contenir" +
                " de caractères interdits";
        }
    }
}

</script>

<template>
    <Form @submit="submit" :class="{'frm-modal': !forget}" :validation-schema="schema" v-slot="{ errors }">
        <ul class="frm-items">
            <li v-if="errorMessage"
                :class="{'error-message': unableUpdate, 'success-message': !unableUpdate }">{{errorMessage}}</li>
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
.success-message {
    color: darkgreen;
}
</style>