<script lang="ts">
import { useMeta } from 'vue-meta'

export default {
    setup() {
        useMeta({
            title: 'Accueil',
            description: "Historiska est une application de collection de cartes en rapport avec des personnages historiques",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
}
</script>

<template>
    <div class="container">
        <h1>Accueil</h1>
    </div>
</template> 

<style scoped lang="scss">
</style>