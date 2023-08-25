<script lang="ts">

import InputComponent from "./InputComponent.vue";
import {defineComponent} from "vue";

export default defineComponent({
    components: {InputComponent},
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
    <form @submit.prevent="handleSubmit">
        <ul class="frm-items">
            <InputComponent type="email" id="usermail" placeholder="Adresse e-mail" required
                            :error-name="usermailError" @updateInputValue="getValue"/>
            <li class="frm-item">
                <button class="btn">Modifier l'adresse e-mail</button>
            </li>
        </ul>
    </form>
</template>

<style scoped lang="scss">

</style>