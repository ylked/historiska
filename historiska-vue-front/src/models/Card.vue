<script setup lang="ts">

// corresponding to structure of card provided by API  

export interface Card {
    id: number,
    quantity: number,
    is_gold: boolean,
    name: string,
    description: string,
    code: string,
    birth: number,
    death: number,
    img: string,
    is_new?: boolean,
    category: {
        name?: string
    },
    country: {
        name?: string
    }
}

</script>