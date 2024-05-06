<template>
  <!-- Button trigger modal -->

  <!-- Modal -->
  <div
    class="modal fade"
    id="modalShow"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="" id="staticBackdropLabel">Detalhes do registro</h1>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            @click="cleanData()"
          ></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-center">
            <img
              :src="
                props.dataShow.data.image
                  ? '/storage/' + props.dataShow.data.image
                  : 'images/loading.gif'
              "
              height="200"
              :alt="props.dataShow.image"
            />
          </div>
          <div>
            <fieldset disabled>
              <legend>Sobre o registro</legend>
                <div class="mb-3" v-for="(data, index) in dataInLabel" :key="index">
                    <field-show-component  :label-title="index" :input-value="data"></field-show-component>
                </div>
            </fieldset>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useDateFormat } from "@vueuse/core";
import { computed, provide, ref } from "vue";


const props = defineProps(["dataShow"]);
let reference = ref()

const emit = defineEmits(["cleanData"]);
//dispara um emit para o pai limpar a variavel pois este componente foi fechado
function cleanData() {
  emit("cleanData", true);
}

//Metodo computado para criar um objeto que bata os label enviados com os dados enviados para popular os inputs
const dataInLabel = computed(() => {
  const data = props.dataShow.data;
  const labels = props.dataShow.labels;
  const dataLabel = {};
  Object.keys(data).map((x) => {

    //percorremos o objeto que contém os metadados das label ex: name: 'nome'
    for (const label in labels) {
      if (x == label) {
        //a variavel receberá a chave labels[label] ex: name e tambem o valor de data[x] ex:Volkswagen
        //se for tipo de data o valor de data[x] será formatado para data, senão recebe o valor de data[x]
        dataLabel[labels[label]] = x == "created_at" || x == "updated_at" ? useDateFormat(data[x], "DD/MM/YYYY").value : data[x];
        //se for a relação de qtd de modelos que uma marca tem(BrandCar)
        dataLabel[labels[label]] = x=="models" ? data[x].length : dataLabel[labels[label]];
        //se for a relação da marca do carro(ModelCar)
        dataLabel[labels[label]] = x=="brand" ? data[x].name : dataLabel[labels[label]];
        //conversão de 1 pra sim e 0 pra não para ABS
        dataLabel[labels[label]] = x=="abs" && data[x] == 1 ? 'Sim' : dataLabel[labels[label]] ;
        dataLabel[labels[label]] = x=="abs" && data[x] == 0 ? 'Não' : dataLabel[labels[label]] ;
        //conversão de 1 pra sim e 0 pra não para AIR BAG
        dataLabel[labels[label]] = x=="air_bag" && data[x] == 1 ? 'Sim' : dataLabel[labels[label]] ;
        dataLabel[labels[label]] = x=="air_bag" && data[x] == 0 ? 'Não' : dataLabel[labels[label]] ;
      }
    }
  });

  return dataLabel;
});
</script>

<style>
.modal-content{
    background: linear-gradient(to left , rgb(93, 91, 91), #999797);
    color: rgb(39, 33, 33);
}
</style>
