<template>
  <div class="form-groupg">
    <label :for="props.labelFor" class="form-label">{{props.labelTitle}}</label>
    <input
      type="text"
      class="form-control"
      :id="props.inputId"
      :placeholder="props.inputPlaceholder"
      :value="props.inputValue"

    />

  </div>
</template>

<script setup>
import { ref, } from "vue"
 const props=defineProps(['labelTitle','labelFor','inputValue','inputType','inputId','inputStyle','inputPlaceholder'])

</script>

<style>
</style>
