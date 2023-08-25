<script setup lang="ts">
  import Nav from "../components/Nav.vue";
  import Decorator from "../components/Decorator.vue";
  import LoginForm from "../components/form/LoginForm.vue";
  import InputComponent from "../components/form/InputComponent.vue";
</script>

<template>
    <Nav />
    <section>
        <div class="content-container">
            <Decorator element="<h1>Profil</h1>" />
            <ul class="list-items list-account-infos">
                <li class="list-item">
                    <input type="text" id="" value="username" disabled>
                    <button type="button" class="btn">Modifier</button>
                </li>
                <li class="list-item">
                    <input type="email" id="" value="exemple@exemple.com" disabled>
                    <button type="button" class="btn">Modifier</button>
                </li>
                <li class="list-item">
                    <input type="password" id="" value="**********" disabled>
                    <button type="button" class="btn">Modifier</button>
                </li>
            </ul>
        </div>
    </section>
</template>

<style scoped lang="scss">
.content-container {
  display: flex;
  align-items: center;
  flex-direction: column;
}

.list-items {
  list-style: none;
  padding: 0;
}

.list-item {
  margin: 15px 0;
  display: flex;
  align-items: center;

  input {
    margin-right: 5px;
  }
}

</style>