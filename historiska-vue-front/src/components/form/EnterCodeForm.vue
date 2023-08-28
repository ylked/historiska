<script lang="ts">

import {defineComponent} from "vue";
import Input from "./Input.vue";

export default defineComponent({
    setup() {

    },
    data(){
        return {
            code: '',
            codeError: '',
        }
    },
    components: {Input},
    methods: {
        handleSubmit() {
            const data = {
                code: this.code
            }
            // POST on /card/activate/{share_code}
        },
        getValue(value, id) {
            this.$data[id] = value;
        }
    }

});
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <ul class="frm-items">
            <Input type="text" required id="code"  placeholder="XXXX-XXXX-XXXX-XXXX" @updateInputValue="getValue" />
            <li class="frm-item">
                <button class="btn">Récupérer</button>
            </li>
        </ul>
    </form>
</template>

<style scoped lang="scss">

</style>