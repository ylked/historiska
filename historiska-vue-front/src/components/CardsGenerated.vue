<script setup lang="ts">
import { computed } from "vue";
import Decorator from "../components/Decorator.vue";
import CardReveal from "../components/CardReveal.vue";
import type { Card } from "../models/Card.vue";

const props = defineProps<{
  cards: Card[]
}>()

const gridStyle = computed(() => {
  return { 
    'grid-template-columns': 'repeat(' + props.cards.length + ', 200px)'
   }
})


</script>

<template>
    <Decorator element="<h1>Vos cartes</h1>" class="title"></Decorator>
    <div class="cards-container" :style="gridStyle">
      <CardReveal v-for="card in cards" :card="card"></CardReveal>
    </div>
</template> 

<style scoped lang="scss">
.title
{
  margin-bottom: 25px;
}
.cards-container {
  display: grid;
  column-gap: 30px;
}
</style>