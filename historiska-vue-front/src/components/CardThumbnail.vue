<script setup lang="ts">
import { shallowRef, ref, computed } from 'vue'
import type { Card } from '../models/Card.vue'
import { useCardTransform } from './animation';

const props = defineProps<{
    cardInfo: Card,
    hideQuantity?: Boolean,
}>()

const isNotOwned = computed(() => {
    return props.cardInfo.quantity < 1 ? true : false;
})

// 3D animation
const target = ref(null)
const { cardTransform } = useCardTransform(target);

// Modal
// Import our inner modal component
import CardExpended from "./CardExpended.vue";

// Import store
import useModalStore from "../stores/useModalStore";

// Initialize store
const store = useModalStore();

// Make a function that opens modal with our inner component
function openExpendedCard() {
    if (isNotOwned.value) return console.log('You do not own this card');
    store.openModal({
        component: shallowRef(CardExpended),
        props: { card: props.cardInfo, hideQuantity: props.hideQuantity },
    });
}

</script>

<template>
    <Transition name="slide-fade" appear>
        <div class="card-container" :class="[(cardInfo.is_gold) ? 'golden' : '', (isNotOwned) ? 'not-owned' : '']"
            ref="target" :style="{
                transform: cardTransform,
                transition: 'transform 0.25s ease-out'
            }" @click="openExpendedCard">

            <div class="quantity" v-if="cardInfo.quantity > 1 && !hideQuantity"><span>{{ cardInfo.quantity
            }}x</span>
            </div>
            <div class="new" v-if="cardInfo.is_new"><span>New</span></div>
            <div class="card-margin">
                <img src="../assets/corner-ornement.svg" alt="" class="svg-icon">
                <div class="text-container">
                    <span class="text">{{ cardInfo.country.name }}</span>
                    <span class="dot"></span>
                    <span class="text">{{ cardInfo.category.name }}</span>
                </div>
            </div>
            <div class="card-body">
                <img :src="cardInfo.img" :alt="cardInfo.description" draggable="false" class="photo">
                <div class="content">
                    <span class="title">
                        {{ cardInfo.name }}
                    </span>
                    <img src="../assets/underline.svg" alt="" class="svg-icon">
                    <span class="birth-death">{{ cardInfo.birth }} à {{ cardInfo.death }}</span>
                </div>
            </div>
        </div>
    </Transition>
</template> 

<style scoped lang="scss">
// style in bases.scss because reused in CardExpended.vue
</style>