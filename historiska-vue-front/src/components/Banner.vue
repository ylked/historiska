<script setup lang="ts">
defineProps<{
    title: string
}>()
// todo add ornement component
</script>

<template>
    <section class="title-banner">
        <h1>{{ title }}</h1>
    </section>
</template> 

<style scoped lang="scss">
.title-banner {
    display: block;
    background-color: $dark-purple;
    padding: 80px 0;

    h1 {
        color: white;
        text-align: center;
        margin: 0;
    }
}
</style>