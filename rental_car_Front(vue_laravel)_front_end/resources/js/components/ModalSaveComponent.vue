<template>
  <!-- Button trigger modal -->

  <!-- Modal -->
  <div
    class="modal fade"
    id="modalCreate"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel"
    aria-hidden="true"
  >
    <!-- responsável por mostrar mensagens de feedback da reposta da api(sucesso ou os erros) para create -->
    <alert-component
      @close="props.alertDataCreate.data.show = $event"
      :alert-color="props.alertDataCreate.data.color"
      :alert-title="props.alertDataCreate.data.title"
      :alert-message="props.alertDataCreate.data.message"
      v-if="props.alertDataCreate.data.show"
    ></alert-component>

    <!-- responsável por mostrar mensagens de feedback da reposta da api(sucesso ou os erros) para update -->
    <alert-component
      @close="props.alertDataUpdate.data.show = $event"
      :alert-color="props.alertDataUpdate.data.color"
      :alert-title="props.alertDataUpdate.data.title"
      :alert-message="props.alertDataUpdate.data.message"
      v-if="props.alertDataUpdate.data.show"
    ></alert-component>

    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
            {{ props.title }}
          </h1>
          <button
            type="button"
            class="btn-close"
            @click="clean()"
            data-bs-dismiss="modal"
          ></button>
        </div>
        <div class="modal-body">
          <field-create-component
            class="mt-3"
            v-for="(label, index) in props.dataCreateLabel"
            :key="index"
            :ref="label[0]"
            :label-title="index"
            input-style="form-control"
            :input-type="label[1]"
            :inputPlaceholder="label[2]"
            :options="label[1].data"
            :optionDefault="label[2]"
            :input-id="label[0]"
            :label-for="label[0]"
          ></field-create-component>

          <!--
          <field-create-component
            class="mt-3"
            ref="logo"

            @outFile="receivedCreateBrandLogo($event)"

            input-placeholder="Insira a imagem do logo da marca"
          >
          </field-create-component> -->
        </div>
        <div class="modal-footer">
          <button
            type="button"
            @click="clean()"
            class="btn btn-danger"
            data-bs-dismiss="modal"
          >
            Fechar
          </button>
          <button type="button" @click="save()" class="btn btn-success">
            Salvar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, reactive, ref, watch, watchEffect } from "vue";
import { useGeralStore } from "../store/dataStore";
//variaveis que contém referencia com o componente field-create para ter acesso a variavel do input para poder limpa-la
const name = ref();
const image = ref();
const brand_id = ref();
const number_doors = ref();
const abs = ref();
const air_bag = ref();
const seats = ref();

const store = useGeralStore();
const props = defineProps(["title", "dataCreateLabel", "alertDataCreate", "alertDataUpdate"]);

let alert = reactive({ title: "", message: "", show: false, color: "" });
let url = ref("http://127.0.0.1:8000/api/v1/brand_car");
const emit = defineEmits(["createData"]);

//se o objeto dataStore estiver preenchido é pq é edição de dados
watchEffect(() => {
  const labels = getLabelsRef();
  const dataStore = store.dataToUpdate
  if (dataStore) {
    Object.values(labels).forEach((x)=>{
        //não podemos preencher o input de imagem pois não é possivel fazer isto com arquivos do tipo file
        if(x!=="image"){
           eval(`${x}.value[0].typedText= dataStore.${x}`)
        }
    })

  }
});

//metódo para salvar inputs
function save() {
  //transformo todas as chaves do objeto props em um array
  const labels = getLabelsRef();
  let form = new FormData();
  //os valores dos inputs neste componente estão sendo recebidos através de template ref do vue e não por emit como em outros (tudo para somente didática)
  Object.values(labels).forEach((x)=>{
    //se imagem a variavel que contém os dados do input é outra
    if(x==="image"){
       eval(`${x}.value[0].typedText!=="" ? form.append("${x}", ${x}.value[0].selectedFile[0]) : '';`)
    }else{
       eval(`${x}.value[0].typedText!=="" ? form.append("${x}", ${x}.value[0].typedText) : '';`)
    }
  })
  //enviado para o componente pai para persistir.
  emit("createData", form);
}

//disparado quando o modal é fechado
//metodo para limpar a variavel typedText que faz binding no imput do componente field-create-component
function clean() {
  const labels = getLabelsRef();
  Object.values(labels).forEach((x)=>{
     //como a variavel typedText faz v-model com os inputs, eu os atribuo vazio através do template ref do vue para os limpar
    eval(`${x}.value[0].typedText=''`)
  })

  //limpo a store
  store.saveDataStore(null)
}


//retorna todas as labels deste modal
function getLabelsRef() {
  //acesso essa variavel através do template ref
  //em field-create-component coloquei a variavel que faz v-model com o valor do input para ser exposta( a variavel typedText ) assim consigo manipula-la deste componente através do template ref
  const labels = [];
  //faço um for para recuperar o nome da variavel recebida por props
  for (const label in props.dataCreateLabel) {
    labels.push(props.dataCreateLabel[label][0]);
  }

  return labels;
}
</script>

<style>
</style>
