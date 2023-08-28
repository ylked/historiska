
  
<script setup lang="ts">
import useModalStore from "../stores/useModalStore";
import { Card } from '../models/Card.vue';

defineProps<{
  card: Card
}>()


// Initialize store
const store = useModalStore();

// TODO récupérer toutes les entity_card correspondante a la "card" sélectionnée
</script>
  
<template>
    <div class="main-container">
        <div class="modal-content">
            <span class="title">Transférer des cartes</span>
            <p>Activer le partage de la carte puis envoyer le code à votre ami:</p>

            <div class="code-container">
                <label class="toggle-switch">
                    <input type="checkbox">
                    <span class="slider round"></span>
                </label>
                <span class="code">XXXX-XXXX-XXXX-XXXX</span>
            </div>
        </div>
        <span class="btn" @click="store.closeModal">Fermer</span>
    </div>
</template>
<style scoped lang="scss">
.main-container
{
    display: grid;
    place-items: center;
}
.modal-content {
    padding: 15px;
    border-radius: 7px;
    background-color: white;
    .title
    {
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