<template>
  <div :class="'alert '+ props.alertColor + ' alert-dismissible fade show'" role="alert">
    <strong>{{props.alertTitle}}!</strong><br>
    <!-- lista de erros se houver erro é claro -->
    <ul v-if="props.alertColor=='alert-danger'">
      <li  v-for="error in props.alertMessage" :key="error.id">{{error}}</li>
    </ul>
    <span v-else>{{props.alertMessage}}</span>
    <button
      type="button"
      class="btn-close"
      @click="closeAlert()"
      aria-label="Close"
    ></button>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from "vue"

 const emit=defineEmits(['close'])
 const props=defineProps(['alertTitle','alertMessage','alertColor'])


/* emit para fechar este alert */
//vai ser chamado no clique do botão ou disparado depois de 5s no onMounted
 function closeAlert(){
    emit('close',false)
 }

//Depois que o alert for chamado se ele não for fechado será disparada a função que contém o emit que dispara para o componente pai fecha-lo em 5 segundos
 onMounted(()=>{
    setTimeout(closeAlert,5000)
 })

</script>

<style>
</style>
