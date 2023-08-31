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
    props: { card_id: props.card.id, is_gold: props.card.is_gold },
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
        <div class="card-container box-front" :class="(card.is_gold) ? 'golden' : ''">

          <div class="quantity" v-if="card.quantity > 1 && !hideQuantity"><span>{{ card.quantity
          }}x</span>
          </div>
          <div class="new" v-if="card.is_new"><span>New</span></div>
          <div class="card-margin">
            <img src="../assets/corner-ornement.svg" alt="" class="svg-icon">
            <div class="text-container">
              <span class="text">{{ card.country.name }}</span>
              <span class="dot"></span>
              <span class="text">{{ card.category.name }}</span>
            </div>
          </div>
          <div class="card-body">
            <img :src="card.img" :alt="card.description" draggable="false" class="photo">
            <div class="content">
              <span class="title">
                {{ card.name }}
              </span>
              <img src="../assets/underline.svg" alt="" class="svg-icon">
              <span class="birth-death">{{ card.birth }} à {{ card.death }}</span>
            </div>
          </div>
        </div>
        <div class="card-back box-back" :class="(card.is_gold) ? 'golden' : ''">
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
  border-radius: 14px;

  .card-margin {
    padding: 10px;
    padding-bottom: 14px;
    border-radius: 0 14px 14px 0;

    .svg-icon {
      width: 30px;
      height: 30px;
    }

    .text {
      font-size: 1.5em;
    }
  }

  .content {
    padding: 17px;
    padding-top: 30px;
    margin-top: -14px;
    border-bottom-right-radius: 14px;

    .title {
      font-size: 2.1em;
    }

    .birth-death {
      font-size: 1.3em;
    }

    .svg-icon {
      margin: 10px 0;
    }
  }

  .photo {
    border-radius: 0 14px 0 14px;
  }

  .quantity {
    width: 60px;
    height: 60px;
    top: -40px;

    span {
      font-size: 1.5em;
    }
  }
}

.card-back {
  padding: 10px;
  background-color: $magenta;

  &.golden {
    background: $shiny;
  }

  .content-container {
    border-radius: 14px;
    background-color: white;
    height: 100%;
    padding: 15px;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
      text-align: justify;
    .description
    {
      font-size: .9em;
    }
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
  border-radius: 14px;
}
</style>