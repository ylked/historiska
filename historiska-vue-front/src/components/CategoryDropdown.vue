<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import CardThumbnail from './CardThumbnail.vue'
import LoadingSpinner from './LoadingSpinner.vue'
import type { Card } from '../models/Card.vue'
import { useCardStore } from '../stores/useCardStore'

let dropdownOpened = ref(false);
let sortedCards = ref<Card[]>([]);
let isLoading = ref(false);

// The `toggleDropdown` function is responsible for toggling the value of the `dropdownOpened` ref
// variable. If it's the first time the dropdown is opened, it will fetch the cards from the API.
function toggleDropdown() {
    dropdownOpened.value = !dropdownOpened.value

    if (sortedCards.value.length == 0) {
        fetchCards();
    }
}

// The `fetchCards` function is responsible for fetching the cards from the API and sort
// them by quantity.
const fetchCards = () => {
    const cardStore = useCardStore();

    isLoading.value = true;
    const cards = cardStore.fetchCardsFromCategory(props.id).then((cards) => {
        sortedCards.value = cards.slice().sort((a, b) => -1 * (a.quantity - b.quantity));
        isLoading.value = false;
    });
};

// The `cardsToShow` variable is a computed property that returns an array of cards.
// If the `only_gold` prop is set to true, it will only return the gold cards.
// If the `show_unowned_cards` prop is set to true, it will return all the cards.
const cardsToShow = computed(() => {
    if (props.only_gold) {
        return sortedCards.value.filter((card: Card) => card.is_gold == true);
    }
    return props.show_unowned_cards ? sortedCards.value : sortedCards.value.filter((card: Card) => card.quantity > 0); // filter unowned cards
});

// The `watch` function is a Vue composition API method that allows you to watch for changes in
// reactive data and perform some action when the data changes.
watch(
    () => props.collapsed,
    (newValue) => {
        dropdownOpened.value = newValue;
        if (sortedCards.value.length == 0) {
            fetchCards();
        }
    }
);

const props = defineProps<{
    id: number,
    name: string,
    owned_quantity: number,
    total_quantity: number,
    show_unowned_cards: Boolean
    collapsed: boolean,
    only_gold: boolean,
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
        <div class="loading-container">
            <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
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
.loading-container {
    display: flex;
    justify-content: center;

    .loading-spinner {
        display: flex;
        align-items: center;
    }
}

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

.cards-container {
    display: grid;
    grid-template-columns: repeat(1, 1fr);
    column-gap: 20px;
    row-gap: 35px;
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