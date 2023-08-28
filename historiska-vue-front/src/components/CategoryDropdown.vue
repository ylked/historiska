<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import CardThumbnail from './CardThumbnail.vue'
import type { Card } from '../models/Card.vue'

let dropdownOpened = ref(true)

function toggleDropdown() {
    dropdownOpened.value = !dropdownOpened.value
}

const sortedCards = ref<Card[]>([]);
const cardsToShow = computed(() => {
    return props.show_unowned_cards ? props.cards : props.cards.filter((card: Card) => card.quantity > 0);
});

onMounted(() => {
    sortedCards.value = props.cards.slice().sort((a: Card, b: Card) => -1 * (a.quantity - b.quantity));
});

const props = defineProps<{
    name: string,
    owned_quantity: number,
    total_quantity: number,
    show_unowned_cards: Boolean,
    cards: Card[]
}>()

</script>

<template>
    <section class="category">
        <div class="category-dropdown" @click="toggleDropdown" :class="{ opened: dropdownOpened }">
            <div class="left-side">
                <span class="arrow">
                </span>
                <span>{{ name }}</span>
            </div>
            <div class="right-side">
                <span>{{ owned_quantity }} / {{ total_quantity }}</span>
            </div>
        </div>
        <Transition name="slide-fade" appear>
            <div class="cards-container" v-if="dropdownOpened">
                <CardThumbnail v-for="card in cardsToShow" class="card" :cardInfo="card">
                </CardThumbnail>
            </div>
        </Transition>
    </section>
</template> 

<style scoped lang="scss">
.category-dropdown {
    border-top: solid 2px black;
    display: flex;
    justify-content: space-between;
    padding: 15px 0 30px;
    position: relative;

    &:hover {
        cursor: pointer;
    }

    &.opened {
        .arrow::after {
            top: 16px;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    }

    .arrow {
        padding: 0 20px;

        &::after {
            content: "";
            position: absolute;
            display: block;
            top: 22px;
            left: 15px;
            width: 10px;
            height: 10px;
            border: solid black;
            border-width: 0 2px 2px 0;
            -webkit-transform: rotate(225deg);
            -ms-transform: rotate(225deg);
            transform: rotate(225deg);
            transition: transform .5s ease-in-out, top .5s ease-in-out 0s;
        }
    }
}

.slide-fade-enter-active {
    transition: all 0.5s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.5s ease-in;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateY(30px);
    opacity: 0;
}

.cards-container {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    column-gap: 20px;
    row-gap: 30px;
}

@media (min-width: 340px) {
    .cards-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 576px) {
    .cards-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 768px) {
    .cards-container {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 992px) {
    .cards-container {
        grid-template-columns: repeat(5, 1fr);
    }
}
</style>