<script lang="ts">

import InputComponent from "./Input.vue";
import {defineComponent} from "vue";
import Decorator from "../Decorator.vue";
import ModalCloseButton from "../ModalCloseButton.vue";

export default defineComponent({
    components: {ModalCloseButton, Decorator, InputComponent},
    data() {
        return {
            noError: false,
            usermail: '',
            usermailError:'',
            formData:{
                token:'',
                email:''
            }
        }
    },
    methods:{
        handleSubmit() {
            // Manage error
            this.handleErrors();

            // Send request
            // TODO test with api
            //this.sendRequest();
        },
        handleErrors() {
            // Nothing
        },
        sendRequest() {
            // Config request
            const requestOptions = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.formData),
            };

            fetch('/account/update/email', requestOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Unable to login request.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle response
                    console.log(data);
                })
                .catch(error => {
                    // Handle errors
                    console.error(error);
                });
        },
        getValue(value, id) {
            this.$data[id] = value;
        }
    }
})

</script>

<template>
    <div class="container-update-modal">
        <ModalCloseButton class="close-modal" />
        <Decorator element="<h1>Modifier</h1>" />
        <p>Veuillez entrer votre notre nouvelle adresse e-mail.</p>
        <form class="frm-modal" @submit.prevent="handleSubmit">
            <ul class="frm-items">
                <InputComponent type="email" id="usermail" placeholder="Adresse e-mail" required
                                :error-name="usermailError" @updateInputValue="getValue"/>
                <li class="frm-item confirm-group-buttons">
                    <button class="btn">Appliquer</button>
                </li>
            </ul>
        </form>
    </div>
</template>

<style scoped lang="scss">

</style>