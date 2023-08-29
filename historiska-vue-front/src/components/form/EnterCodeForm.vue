<script lang="ts">

import { defineComponent } from "vue";
import { Card } from "../../models/Card.vue";
// Modal
import CardExpended from "../CardExpended.vue";
import useModalStore from "../../stores/useModalStore";

export default defineComponent({
    data() {
        return {
            code: '',
            placeholder: 'XXXX-XXXX-XXXX-XXXX',
            codeError: '',
            card: {}
        }
    },
    methods: {
        handleSubmit() {
            const share_code = this.code
            // POST on /card/activate/{share_code}
            let newCard: Card;
            // newCard = await this.$axios.$post('/card/activate/' + this.code, data);
            { // debug
                newCard = {
                    name: 'Platon',
                    quantity: 3,
                    description: 'ceci est une description',
                    code: '007',
                    birth: -400,
                    death: -320,
                    image_path: './platon.png',
                    is_golden: true,
                    category: {
                        name: 'Philosophe'
                    },
                    country: {
                        name: 'Grèce'
                    }
                }
            }

            const store = useModalStore();
            store.openModal({
                component: CardExpended,
                props: { card: newCard, hideQuantity: true },
            });

            // reset form
            this.code = '';
        },
        getValue(value, id) {
            this.$data[id] = value;
        },
        updateCode(value) {
            let text = value.target.value;
            let formattedText = "";

            // Delete - already there to have the real value
            let realValue = text.replace(/-/g, '');

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
    <form @submit.prevent="handleSubmit">
        <ul class="frm-items">
            <li class="frm-item">
                <input type="text" id="code" v-model="code" :placeholder="placeholder" ref="inputCode" required
                    @input="updateCode" maxlength="19" autocomplete="off">
            </li>
            <li class="frm-item">
                <button class="btn">Ajouter à la collection</button>
            </li>
        </ul>
    </form>
</template>

<style scoped lang="scss"></style>