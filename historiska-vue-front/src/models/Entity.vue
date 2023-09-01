<script setup lang="ts">

// corresponding to structure of entity provided by API 

export interface Entity {
    entity_id: number,
    is_shared: boolean,
    share_code?: string,
    is_gold?: boolean
}

</script>