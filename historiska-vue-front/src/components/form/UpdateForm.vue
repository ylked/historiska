<script lang="ts">

import Decorator from "../Decorator.vue";
import ModalCloseButton from "../ModalCloseButton.vue";
import {defineComponent} from "vue";
import MailForm from "./MailForm.vue";
import PasswordForm from "./PasswordForm.vue";
import UsernameForm from "./UsernameForm.vue";

export default defineComponent({
    components: {Decorator, ModalCloseButton},
    props: {
        title: String,
        text: String,
        componentName: String
    },
    setup() {
        const tabForm = {
            MailForm,
            PasswordForm,
            UsernameForm
        }

        return {tabForm}
    }
})

</script>

<template>
    <div class="container-update-modal">
        <ModalCloseButton class="close-modal" />
        <Decorator :element="'<h1>' + title + '</h1>'" />
        <p>{{text}}</p>
        <component :is="tabForm[componentName]" />
    </div>
</template>

<style scoped lang="scss">

</style>