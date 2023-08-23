<script lang="ts">
import { useMeta } from 'vue-meta'
import Nav from '../components/Nav.vue'

export default {
    setup() {
        useMeta({
            title: 'Collection',
            description: "La page collection est déstinée à visualiser la collection de carte que le joueur actuellement connecté possède",
            htmlAttrs: { lang: 'fr', amp: true }
        })
    },
    components: {
        Nav
    }
}
</script>

<template>
    <Nav></Nav>
    <div class="container">
        <h1>Collection</h1>
    </div>
</template> 

<style scoped lang="scss"></style>