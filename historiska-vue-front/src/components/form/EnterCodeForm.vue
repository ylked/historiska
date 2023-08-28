<script lang="ts">

import {defineComponent} from "vue";

export default defineComponent({
    data(){
        return {
            code: '',
            placeholder: 'XXXX-XXXX-XXXX-XXXX',
            codeError: '',
        }
    },
    methods: {
        handleSubmit() {
            const data = {
                code: this.code
            }
            // POST on /card/activate/{share_code}
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
                <button class="btn">Récupérer</button>
            </li>
        </ul>
    </form>
</template>

<style scoped lang="scss">

</style>