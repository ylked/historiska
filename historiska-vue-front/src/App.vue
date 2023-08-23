<script setup lang="ts">
import Nav from './components/Nav.vue'
import { RouterView } from 'vue-router'
</script>

<template>
  <Nav></Nav>
  <RouterView/>
</template>

<style lang="scss">
@import './styles/bases.scss'; // import bases and apply to all component
</style>
