<script setup lang="ts">
import Decorator from "./Decorator.vue"

defineProps<{
    title: string
}>()

</script>

<template>
    <section class="title-banner">
        <Decorator :element="'<h1>' + title + '</h1>'" :white="true" text-color="white"></Decorator>
    </section>
</template> 

<style scoped lang="scss">
.title-banner {
    display: flex;
    justify-content: center;
    background-color: $dark-purple;
    padding: 80px 0;

    h1 {
        color: white;
        text-align: center;
        margin: 0;
    }
}
</style>