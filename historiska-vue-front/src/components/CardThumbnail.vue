<script setup lang="ts">
import { ref, computed } from 'vue'
import { useMouseInElement } from '@vueuse/core'
import Modal from './Modal.vue'
import type { Card } from '../models/Card.vue'

defineProps<{
    cardInfo: Card,
}>()

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

// Modal
// Import our inner modal component
import InfoModal from "./InfoModal.vue";

// Import store
import useModalStore from "../stores/useModalStore";

// Initialize store
const store = useModalStore();

// Make a function that opens modal with our inner component
function openInfoModal() {
  store.openModal({ component: InfoModal });
}

</script>

<template>
    <div>
        <Modal></Modal>
        <div class="card-container" ref="target" :style="{
            transform: cardTransform,
            transition: 'transform 0.25s ease-out'
        }" @click="openInfoModal">
            <div class="top">
                <div class="quantity" v-if="cardInfo.quantity > 1"><span>{{ cardInfo.quantity }}x</span></div>
                <span class="title">
                    {{ cardInfo.name }}
                </span>
                <span class="birth-death">{{ cardInfo.birth }} à {{ cardInfo.death }}</span>
            </div>
            <div class="middle">
                <img :src="cardInfo.image_path" alt="">
            </div>
            <div class="bottom">
                <span class="bolded-text">{{ cardInfo.category.name }}</span>
                <span class="bolded-text">{{ cardInfo.country.name }}</span>
            </div>
        </div>
    </div>
</template> 

<style scoped lang="scss">
.card-container {
    background-color: #69666D;
    color: white;
    border-radius: 7px;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none;
    box-shadow: rgba(60, 60, 60, 0.5) 3px 3px 15px;

    &>div {
        padding: 10px;
    }

    &:hover {
        cursor: pointer;
    }

    .top {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        padding-top: 15px;

        .quantity {
            position: absolute;
            background-color: $purple;
            border-radius: 100px;
            width: 40px;
            height: 40px;
            top: -20px;
            display: flex;
            justify-content: center;
            align-items: center;

            span {
                position: absolute;
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
    }

    .title {
        font-weight: 500;
        font-size: 1.5em;
    }

    .birth-death {
        font-weight: 100;
        font-size: .8em;
    }

    img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 7px;
    }

    .bolded-text {
        font-weight: 500;
    }
}
</style>