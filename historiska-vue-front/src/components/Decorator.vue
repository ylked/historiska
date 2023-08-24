<script lang="ts">
import {defineComponent} from "vue";
export default defineComponent({
    props:{
        element:Object
    }
});
</script>

<template>
    <div class="decoration-title">
        <img src="../assets/ornement.svg" alt="Ornement" class="decoration-left">
        <div v-html="element"></div>
        <img src="../assets/ornement.svg" alt="Ornement" class="decoration-right">
    </div>
</template>

<style scoped lang="scss">

</style>