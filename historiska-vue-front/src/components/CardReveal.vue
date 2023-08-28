<script setup lang="ts">
import { ref } from "vue";
import type { Card } from "../models/Card.vue";
import CardThumbnail from './CardThumbnail.vue';

defineProps<{
    card: Card
}>()

const revealed = ref(false);

</script>

<template>
    <div>
        <Transition name="bounce">
            <div v-if="!revealed" class="reveal-container" @click="revealed = true" title="Clique pour révéler">
                <span>?</span>
            </div>
        </Transition>
        <Transition name="card-appear">
            <CardThumbnail v-if="revealed" :card-info="card" :hide-quantity='true' class="opacity"></CardThumbnail>
        </Transition>
    </div>
</template> 

<style scoped lang="scss">
.reveal-container {
    background-color: $purple;
    position: absolute;
    border-radius: 7px;
    box-shadow: rgba(60, 60, 60, 0.3) 0px 0px 15px;
    height: 374px;
    width: 200px;
    z-index: 499;
    display: grid;
    place-items: center;
    transition: box-shadow 0.25s ease-out;

    span {
        font-family: $title-font;
        font-size: 7em;
        color: white;
        margin-right: 14px;
    }

    &:hover {
        box-shadow: $light-purple 0px 0px 15px;
    }
}

.card-appear-enter-active,
.card-appear-leave-active {
    animation: card-appear 3s ease-in-out;
}

@keyframes card-appear {
    0% {
        transform: scale(0);
    }

    20% {
        transform: scale(0);
    }

    100% {
        transform: scale(1);
    }
}

.bounce-enter-active {
    animation: bounce-in 2s;
}

.bounce-leave-active {
    animation: bounce-in 2s reverse;
}

@keyframes bounce-in {
    0% {
        transform: scale(0);
    }

    20% {
        transform: scale(1.2);
    }

    30% {
        transform: scale(1.2);
    }

    100% {
        transform: scale(1);
    }
}
</style>