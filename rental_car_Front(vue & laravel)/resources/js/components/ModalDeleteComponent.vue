<template>
  <!-- Button trigger modal -->

  <!-- Modal -->
  <div
    class="modal fade"
    id="modalDelete"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <!-- responsável por mostrar mensagens de feedback da reposta da api(sucesso ou os erros) -->
    <alert-component
      @close="props.alertData.data.show = $event"
      :alert-color="props.alertData.data.color"
      :alert-title="props.alertData.data.title"
      :alert-message="props.alertData.data.message"
      v-if="props.alertData.data.show"
    ></alert-component>

    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="" id="staticBackdropLabel">Exclusão de dados</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
          ></button>
        </div>

        <div v-if="props.success == true">
          <div class="modal-body">
            <p>Dados excluidos com sucesso!</p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-success"
              data-bs-dismiss="modal"

            >
              Sair
            </button>
          </div>
        </div>

        <div v-else>
          <div class="modal-body">
            <p>Você tem certeza que deseja excluir estes dados?</p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-success"
              data-bs-dismiss="modal"

            >
              Cancelar
            </button>
            <button type="button" @click="deleteData()" class="btn btn-danger">
              Deletar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useDateFormat } from "@vueuse/core";
import { storeToRefs } from "pinia";
import { useGeralStore } from "../store/dataStore";

const props = defineProps(["alertData", "success"]);

const emit = defineEmits(["deleteData"]);
//dispara um emit para o pai limpar a variavel pois este componente foi fechado
function deleteData() {
  emit("deleteData", true);
}


</script>

<style>
</style>
