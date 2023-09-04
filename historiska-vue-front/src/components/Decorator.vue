<script lang="ts">
import { defineComponent } from "vue";
export default defineComponent({
    props: {
        element: String,
        white: Boolean,
        textColor: String,
        subPath: Boolean,
    },
    computed: {
        style() {
            return this.textColor ? 'color: ' + this.textColor : '';
        },
        imagePath() {
            if (this.subPath) {
                return this.white ? '../../src/assets/ornement-white.svg' : '../../src/assets/ornement.svg';
            } else {
                return this.white ? '../src/assets/ornement-white.svg' : '../src/assets/ornement.svg';
            }
        }
    },
});
</script>

<template>
    <div class="decoration-title">
        <img :src="imagePath" alt="Ornement" class="decoration-left">
        <div v-html="element" :style="style"></div>
        <img :src="imagePath" alt="Ornement" class="decoration-right">
    </div>
</template>

<style scoped lang="scss">
.decoration-title {
    display: flex;
    align-items: center;

    img {
        width: 150px;
    }

    .decoration-left {
        margin-right: 30px;
    }

    .decoration-right {
        margin-left: 30px;
        -webkit-transform: scaleX(-1);
        -moz-transform: scaleX(-1);
        -o-transform: scaleX(-1);
        transform: scaleX(-1);
    }
}

@media (max-width: 768px) {
    .decoration-title img {
        width: 100px;
    }
}

@media (max-width: 446px) {
    img {
        display: none;
    }
}
</style>