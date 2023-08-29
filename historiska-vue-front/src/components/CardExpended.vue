<script setup lang="ts">
import { ref } from 'vue';
import { Card } from '../models/Card.vue';
import ModalCloseButton from './ModalCloseButton.vue';
import { useCardTransform } from './animation';

let cardFlipped = ref(false);

const props = defineProps<{
  card: Card
  hideQuantity?: Boolean,
}>()

function flipCard() {
  cardFlipped.value = !cardFlipped.value;
}

// 3D animation
const target = ref(null);
const { cardTransform } = useCardTransform(target);

// openTransferCodes
import TransferCodes from "./TransferCodes.vue";
import useModalStore from "../stores/useModalStore";

// Initialize store
const store = useModalStore();

// Make a function that opens modal with our inner component
function openTransferCodes() {
  store.openModal({
    component: TransferCodes,
    props: { card: props.card },
  });
}
</script>

<template>
  <div class="main-container">
    <ModalCloseButton />
    <div class="box" @click="flipCard" ref="target" :style="{
      transform: cardTransform,
      transition: 'transform 0.25s ease-out'
    }">
      <div class="box-inner" :style="cardFlipped ? 'transform: rotateY(180deg);' : ''">
        <div class="card-container box-front" :class="(card.is_golden) ? 'golden' : ''">
          <div class="card-header">
            <div class="quantity" v-if="card.quantity > 1 && !hideQuantity"><span>{{ card.quantity }}x</span></div>
            <div class="new" v-if="card.is_new"><span>New</span></div>
            <span class="title">
              {{ card.name }}
            </span>
            <span class="birth-death">{{ card.birth }} à {{ card.death }}</span>
          </div>
          <div class="card-body">
            <div class="middle">
              <img :src="card.image_path" :alt="card.description" draggable="false">
            </div>
            <div class="bottom">
              <span class="bolded-text">{{ card.category.name }}</span>
              <span class="bolded-text">{{ card.country.name }}</span>
            </div>
          </div>
        </div>
        <div class="card-back box-back" :class="(card.is_golden) ? 'golden' : ''">
          <div class="content-container">
            <span class="description">{{ card.description }}</span>
          </div>
        </div>
      </div>
    </div>
    <span v-if="card.quantity > 1 && !hideQuantity" class="btn" @click="openTransferCodes">Transférer les doubles</span>
  </div>
</template>
  
<style scoped lang="scss">
.main-container {
  display: grid;
  place-items: center;
  row-gap: 20px;

  button {
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

  &.golden {
    background: $shiny;
  }

  .content-container {
    border-radius: 7px;
    background-color: white;
    height: 100%;
    padding: 15px;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
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