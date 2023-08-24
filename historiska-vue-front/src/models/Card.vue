<script setup lang="ts">

export interface Card {
    name?: string,
    quantity?: number,
    description?: string,
    code?: string,
    birth?: number,
    death?: number,
    image_path?: string,
    category?: {
        name: string
    },
    country?: {
        name: string
    }
}

</script>