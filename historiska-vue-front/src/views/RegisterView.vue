<script setup lang="ts">
  import Nav from "../components/Nav.vue";
  import RegisterForm from "../components/form/RegisterForm.vue";
</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <div class="decoration-title">
                <img src="../assets/ornement.svg" alt="Ornement" class="decoration-left">
                <h1>Inscription</h1>
                <img src="../assets/ornement.svg" alt="Ornement" class="decoration-right">
            </div>

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