<script setup lang="ts">

import { Card } from '../models/Card.vue';

defineProps<{
  card: Card
}>()

</script>

<template>
  <div class="card-container">
    <h2>{{ card.name }}</h2>
    <p>You have done such great work! Congratulations on reaching this step!</p>
  </div>
</template>
  
<style scoped lang="scss">
.card-container
{
  background-color: white ;
}
</style>