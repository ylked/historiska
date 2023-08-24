<script setup lang="ts">
import { ref } from 'vue'
let dropdownOpened = ref(true)

function toggleDropdown() {
    dropdownOpened.value = !dropdownOpened.value
}

defineProps<{
    name: string,
    owned_quantity: number,
    total_quantity: number,
    cards: Object
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
                <div v-for="card in cards" class="card">
                    {{ card.name }}
                </div>
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
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;

    .card {
        height: 200px;
        background-color: lightgray;
    }
}
</style>