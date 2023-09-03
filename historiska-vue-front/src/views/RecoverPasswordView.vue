<script lang="ts">
import {defineComponent} from "vue";
import Decorator from "../components/Decorator.vue";
import ForgetPasswordForm from "../components/form/ForgetPasswordForm.vue";
import PasswordForm from "../components/form/PasswordForm.vue";
import Nav from "../components/Nav.vue";

export default defineComponent({
    data() {
        return {
            token: ''
        }
    },
    components: {Nav, PasswordForm, ForgetPasswordForm, Decorator},
    mounted() {
        this.token = this.$route.params.token;
    }
})

</script>

<template>
    <div>
        <Nav />
        <section>
            <div class="content-container">
                <Decorator element="<h1>Nouveau mot de passe</h1>" class="title"/>
                <PasswordForm forget :token="token" />
            </div>
        </section>
    </div>
</template>

<style scoped lang="scss">
.content-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 90vh;
}

</style>