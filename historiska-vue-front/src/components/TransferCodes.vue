
  
<script setup lang="ts">
import useModalStore from "../stores/useModalStore";
import { Entity } from '../models/Entity.vue';
import { ref, onMounted } from "vue";

const props = defineProps<{
    card_id: number,
    is_gold: boolean
}>()

let entites = ref<Entity[]>();

onMounted(() => {
    // POST request all /entities/{card_id}
    // entites = await...

    entites.value = [
        {
            id: 1,
            is_shared: false,
            share_code: undefined,
            is_gold: false
        },
        {
            id: 2,
            is_shared: true,
            share_code: "ABJF-ANEJ-EJDN-AOPI",
            is_gold: false
        },
        {
            id: 3,
            is_shared: false,
            share_code: undefined,
            is_gold: true
        },
        {
            id: 4,
            is_shared: true,
            share_code: "ISGO-LDEN-CARD-XXXX",
            is_gold: true
        }
    ];

    // filter entity and remove the one that is_gold !== props.is_gold
    entites.value = entites.value.filter(entity => entity.is_gold === props.is_gold);
})

// Initialize store
const store = useModalStore();

</script>
  
<template>
    <div class="main-container">
        <div class="modal-content">
            <span class="title">Transférer des cartes</span>
            <p>Activer le partage de la carte puis envoyer le code à votre ami:</p>

            <div class="code-container" v-for="entity in entites">
                <label class="toggle-switch">
                    <input type="checkbox" v-model="entity.is_shared">
                    <span class="slider round"></span>
                </label>
                <span class="code">{{ entity.share_code || "XXXX-XXXX-XXXX-XXXX" }}</span>
            </div>
        </div>
        <span class="btn" @click="store.closeModal">Fermer</span>
    </div>
</template>
<style scoped lang="scss">
.main-container {
    display: grid;
    place-items: center;
}

.modal-content {
    padding: 15px;
    border-radius: 7px;
    background-color: white;

    .title {
        font-size: 1.2rem;
        margin-bottom: 10px;
    }
}

.code-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
}

.btn {
    margin-top: 30px;
    display: inline-block;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;

    input {
        opacity: 0;
        width: 0;
        height: 0;
    }
}


/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 6px;
    left: 6px;
    right: 9px;
    bottom: 9px;
    background-color: #DEDEDE;
    -webkit-transition: .4s;
    transition: .4s;

    &::before {
        position: absolute;
        content: "";
        top: -3px;
        height: 24px;
        width: 24px;
        background-color: #777777;
        -webkit-transition: .4s;
        transition: .4s;
    }
}

input:checked+.slider:before {
    background-color: $purple;
}

input:focus+.slider:before {
    box-shadow: 0 0 1px $purple;
}

input:checked+.slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
</style>