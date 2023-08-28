<script setup lang="ts">
  import Nav from "../components/Nav.vue";
  import Decorator from "../components/Decorator.vue";
  import RegisterForm from "../components/form/RegisterForm.vue";
</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <Decorator element="<h1>Inscription</h1>" class="title" />
            <RegisterForm />
            <div>
                <p>Déjà un compte ? <RouterLink :to="{ name: 'Connexion' }">Connecte-toi !</RouterLink></p>
            </div>
        </div>
    </section>
</template>

<style scoped lang="scss">
  .content-container {
      display: flex;
      align-items: center;
      flex-direction: column;

      .title
      {
          margin-top: 70px;
          margin-bottom: 25px;
      }
  }


</style>