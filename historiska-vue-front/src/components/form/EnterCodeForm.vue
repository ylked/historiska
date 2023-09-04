<script lang="ts">

import { ref, defineComponent } from "vue";
import { Card } from "../../models/Card.vue";
// Modal
import CardExpended from "../CardExpended.vue";
import useModalStore from "../../stores/useModalStore";
import { useCardStore } from "../../stores/useCardStore";
import LoadingSpinner from "../LoadingSpinner.vue";

export default defineComponent({
    components: {LoadingSpinner},
    data() {
        return {
            code: '',
            placeholder: 'XXXX-XXXX-XXXX-XXXX',
            codeError: '',
            card: {},
            isLoading: false
        }
    },
    methods: {
        async handleSubmit() {
            let newCard = ref<Card>();
            const cardStore = useCardStore();
            // fetch new card from form code
            this.isLoading = true
            cardStore.fetchCardFromCode(this.removeDashes(this.code)).then(response => {
                this.isLoading = false;
                console.log(response);
                if (!response.success && response.status == 404) {
                    this.codeError = "Ce code est invalide.";
                    return;
                }
                newCard.value = response.content
                
                const store = useModalStore();
                store.openModal({
                    component: CardExpended,
                    props: { card: newCard.value, hideQuantity: true },
                });
                cardStore.clearStore();
            });

            // reset form
            this.code = '';
            this.codeError = '';
        },
        removeDashes(inputCode: string): string {
            return inputCode.replace(/-/g, '');
        },
        updateCode(value) {
            let text = value.target.value;
            let formattedText = "";

            // Delete - already there to have the real value
            // Only alphanumeric caracters : A-Z and a-z and 0-9
            let realValue = text.replace(/[^A-Za-z0-9]/g, '');

            for (let i = 0; i < realValue.length; ++i) {
                if (i > 0 && i % 4 === 0) {
                    formattedText += "-";
                }
                formattedText += realValue[i];
            }
            this.code = formattedText.toUpperCase();
        }
    }
});
</script>

<template>
    <form @submit.prevent="handleSubmit" class="small-container">
        <ul class="frm-items">
            <li class="frm-item">
                <p v-if="codeError" class="frm-error-message">{{ codeError }}</p>
                <input type="text" id="code" v-model="code" :placeholder="placeholder" ref="inputCode" required
                    @input="updateCode" maxlength="19" autocomplete="off">
            </li>
            <li class="frm-item btn-submit-loading">
                <LoadingSpinner v-if="isLoading" class="loading-spinner"></LoadingSpinner>
                <button class="btn">Ajouter à la collection</button>
            </li>
        </ul>
    </form>
</template>

<style scoped lang="scss"></style>