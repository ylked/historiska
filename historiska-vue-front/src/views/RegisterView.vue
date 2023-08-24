<script setup lang="ts">
  import Nav from "../components/Nav.vue";
  import Decorator from "../components/Decorator.vue";
  import RegisterForm from "../components/form/RegisterForm.vue";
</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <Decorator element="<h1>Inscription</h1>" />
            <RegisterForm />
        </div>
    </section>
</template>

<style scoped lang="scss">
  .content-container {
    display: flex;
    align-items: center;
    flex-direction: column;
  }
</style>