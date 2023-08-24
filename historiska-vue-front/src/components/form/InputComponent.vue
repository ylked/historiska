<script lang="ts">

  import {defineComponent} from "vue";

  export default defineComponent({
      props:{
          type: String,
          id: String,
          placeholder: String,
          required: Boolean,
          errorName: String,
      },
      methods:{
          updateInput (e) {
              this.$emit('updateInputValue', e.target.value, e.target.id);
          }
      }
  })
</script>

<template>
    <li class="frm-item">
        <input :type="type" :id="id" :placeholder="placeholder" :required="required"
                :class="{'frm-error-field' : errorName }" @input="updateInput">
        <div class="frm-error-message" v-if="errorName">{{ errorName }}</div>
    </li>
</template>

<style scoped lang="scss">
  input {
    width: 100%;
    padding: 10px;
    outline: none;
    border: solid 3px black;
    box-shadow: none;
    border-radius: 30px;
  }

  .frm-item {
      margin: 30px 0;
      text-align: center;
  }

  .frm-error-message
  {
      margin: 10px 0 0 15px;
      color: darkred;
      text-align: left;
      font-weight: bold;
  }

  .frm-error-field
  {
      border-color: darkred;
  }
</style>