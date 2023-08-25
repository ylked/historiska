<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue';
import useModalStore from "../stores/useModalStore";

const store = useModalStore();

// close modal when escape key is pressed
function keydownListener(event: KeyboardEvent) {
    if (event.key === "Escape") store.closeModal();
}

onMounted(() => {
    document.addEventListener("keydown", keydownListener);
});

onUnmounted(() => {
    document.removeEventListener("keydown", keydownListener);
});
</script>

<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div class="modal-wrapper" @click.self="store.closeModal" v-if="store.modalState?.component" aria-modal="true"
                role="dialog" tabindex="-1">
                <component :is="store.modalState?.component" v-bind="store.modalState?.props" />
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped lang="scss">
.modal-wrapper {
    position: fixed;
    left: 0;
    top: 0;
    z-index: 500;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
    display: grid;
    place-items: center;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: .3s ease all;
}
</style>
