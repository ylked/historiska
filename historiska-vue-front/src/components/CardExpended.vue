<script setup lang="ts">
import { ref, computed } from 'vue';
import { Card } from '../models/Card.vue';
import { useMouseInElement } from '@vueuse/core'
import ModalCloseButton from './ModalCloseButton.vue';

let cardFlipped = ref(false);

defineProps<{
  card: Card
}>()

function flipCard() {
  cardFlipped.value = !cardFlipped.value;
}

// 3D animation
const target = ref(null)

const { elementX, elementY, isOutside, elementHeight, elementWidth } =
  useMouseInElement(target)

const cardTransform = computed(() => {
  const MAX_ROTATION = 6

  const rX = (
    MAX_ROTATION / 2 -
    (elementY.value / elementHeight.value) * MAX_ROTATION
  ).toFixed(2) // handles x-axis

  const rY = (
    (elementX.value / elementWidth.value) * MAX_ROTATION -
    MAX_ROTATION / 2
  ).toFixed(2) // handles y-axis

  return isOutside.value
    ? ''
    : `perspective(${elementWidth.value}px) rotateX(${rX}deg) rotateY(${rY}deg)`
})

</script>

<template>
  <div class="main-container">
    <ModalCloseButton />
    <div class="box" @click="flipCard" ref="target" :style="{
      transform: cardTransform,
      transition: 'transform 0.25s ease-out'
    }">
      <div class="box-inner" :style="cardFlipped ? 'transform: rotateY(180deg);' : ''">
        <div class="card-container box-front">
          <div class="card-header">
            <div class="quantity" v-if="card.quantity > 1"><span>{{ card.quantity }}x</span></div>
            <span class="title">
              {{ card.name }}
            </span>
            <span class="birth-death">{{ card.birth }} à {{ card.death }}</span>
          </div>
          <div class="card-body">
            <div class="middle">
              <img :src="card.image_path" alt="">
            </div>
            <div class="bottom">
              <span class="bolded-text">{{ card.category.name }}</span>
              <span class="bolded-text">{{ card.country.name }}</span>
            </div>
          </div>
        </div>
        <div class="card-back box-back">
          <div class="content-container">
            <span class="description">{{ card.description }}</span>
          </div>
        </div>
      </div>
    </div>
    <span v-if="card.quantity > 1" class="btn">Transférer les doubles</span>
  </div>
</template>
  
<style scoped lang="scss">
.main-container {
  display: grid;
  place-items: center;
  row-gap: 20px;
  button
  {
    position: absolute;
    top: 50px;
    right: 50px;
  }
}

.card-container {
  height: auto;
  padding: 15px;

  .card-header {
    padding-top: 0px;
    padding-bottom: 0px;

    .quantity {
      width: 60px;
      height: 60px;
      top: -50px;

      span {
        font-size: 1.5em;
      }
    }
  }

  .middle {
    padding-top: 0;
    padding-bottom: 0;
  }

  .bottom {
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
  }

  .title {
    font-weight: 500;
    font-size: 2em;
  }

  .birth-death {
    font-weight: 100;
    font-size: 1em;
  }

  .bolded-text {
    font-weight: 500;
    font-size: 1.5em;
  }
}

.card-back {
  padding: 10px;
  background-color: $light-purple;

  .content-container {
    border-radius: 7px;
    background-color: white;
    height: 100%;
    padding: 15px;
  }
}

.box {
  width: 322px;
  height: 568px;
  perspective: 1000px;
}

.box-inner {
  position: relative;
  width: 100%;
  height: 100%;
  text-align: center;
  transition: transform 0.5s;
  transform-style: preserve-3d;
}

.box-front,
.box-back {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  cursor: pointer;
}

.box-back {
  transform: rotateY(180deg);
  border-radius: 7px;
}
</style>