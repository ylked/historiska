<script setup lang="ts">

// corresponding to structure of category provided by API 

export interface Category {
    completion_percent: number,
    id: number,
    name: string,
    owned_qty: number,
    total_qty: number
}

</script>