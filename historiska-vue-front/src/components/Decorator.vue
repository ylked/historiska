<script lang="ts">
import {defineComponent} from "vue";
export default defineComponent({
    props:{
        element:Object,
        white: Boolean,
    }
});
</script>

<template>
    <div class="decoration-title">
        <img v-if="white" src="../assets/ornement-white.svg" alt="Ornement" class="decoration-left">
        <img v-else src="../assets/ornement.svg" alt="Ornement" class="decoration-left">
        <div v-html="element"></div>
        <img v-if="white" src="../assets/ornement-white.svg" alt="Ornement" class="decoration-right">
        <img v-else src="../assets/ornement.svg" alt="Ornement" class="decoration-right">
    </div>
</template>

<style scoped lang="scss">
</style>