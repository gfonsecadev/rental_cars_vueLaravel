<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h3>Marcas de carros</h3>
            <a
              type="button"
              data-bs-toggle="modal"
              data-bs-target="#modalCreate"
            >
              <i
                class="bi bi-plus-circle-fill text-primary"
                style="font-size: 25px"
              ></i>
            </a>
          </div>

          <div class="card-body">
            <form>
              <div class="row">
                <field-create-component
                  ref="clean1"
                  @out="receivedId($event)"
                  class="col-6"
                  input-style="form-control"
                  label-for="brandId"
                  label-title="Identificador"
                  input-type="number"
                  input-id="brandId"
                  input-placeholder="Insira um identificador de uma marca"
                >
                </field-create-component>

                <field-create-component
                  ref="clean2"
                  @out="receivedBrandName($event)"
                  class="col-6"
                  input-style="form-control"
                  label-for="brandName"
                  label-title="Marca"
                  input-type="text"
                  input-id="brandName"
                  input-placeholder="Insira um nome de uma marca"
                >
                </field-create-component>
              </div>
            </form>
          </div>
         <div
            class="card-footer d-flex justify-content-between mt-3 form-check form-switch"
          >
            <button class="btn btn-outline-success" @click="cleanInputs()"> Limpar Filtro</button>

            <transition-group
              enter-active-class="animate__animated animate__fadeIn"
              leave-active-class="animate__animated animate__fadeOut"
            >
              <field-create-component
                v-if="dataBrands.data == ''"
                @outSwitch="receivedSelectAll($event)"
                input-style="form-check-input"
                label-for="showAll"
                label-title="Mostrar todas as Marcas disponíveis"
                input-type="checkbox"
                input-id="brandId"
              ></field-create-component>
            </transition-group>
          </div>
        </div>
      </div>
    </div>

    <transition-group
      enter-active-class="animate__animated animate__backInUp"
      leave-active-class="animate__animated animate__backOutDown"
    >
      <table-component
        class="mt-3"
        @updatePage="retrieveBrandApi($event)"
        @searchDataApi="retrieveOneBrand($event)"
        v-if="dataBrands.data.length > 0"
        :table="table"
      >
      </table-component>
    </transition-group>

    <!-- será chamado pelo click na tag a aqui na linha 11 -->
    <modal-save-component
      @createData="createORupdate($event)"
      title="Cadastrar nova marca de carro"
      :data-create-label="brandCreateLabels"
      :alert-data-create="brandAlertCreate"
      :alert-data-update="brandAlertUpdate"
    >
    </modal-save-component>

    <!-- será chamado pelo click na tag i no componente table linha 25 -->
    <modal-show-component
      @cleanData="cleanBrand($event)"
      :data-show="brandShow"
    ></modal-show-component>

    <modal-delete-component
      @deleteData="deleteBrand()"
      :alert-data="brandAlertDelete"
      :success="brandDelete"
    >
    </modal-delete-component>
  </div>
</template>

<script setup>
import TableComponent from "./TableComponent.vue";
import {
  ref,
  computed,
  onMounted,
  reactive,
  onBeforeMount,
  nextTick,
  toRef,
} from "vue";
import { useFetchApi } from "../composables/searchApi";
import { deleteApi } from "../composables/deleteApi";
import { searchOneData } from "../composables/searchOneApi";
import { createData } from "../composables/createApi";
import { updateData } from "../composables/updateApi";
import { formedUrl } from "../composables/formedUrlQuery";
import { reloadApplication } from "../composables/reloadPage";
import { toReactive } from "@vueuse/core";
import { useGeralStore } from "../store/dataStore";

//não utilizei o eval e o window para criar dinamicamente pois não funcionou,somente funcionou para limpar
//variavéis para fazer template ref com os inputs(para poder limpa-los)
const clean1 = ref();
const clean2 = ref();

const store = useGeralStore();
let dataBrands = reactive({ data: [], configPag: {} });
let brandShow = reactive({
  labels: {
    id: "Identificador",
    name: "Nome",
    created_at: "Data de criação",
    updated_at: "Data de atualização",
    models: "Número de modelos cadastrados",
  },
  data: {},
});
let brandCreateLabels = reactive({
  Nome: ["name", "text"],
  Logo: ["image", "file"],
});
let brandAlertCreate = reactive({ data: {} });
let brandAlertUpdate = reactive({ data: {} });
let brandAlertDelete = reactive({ data: {} });
let brandDelete = ref(false); //variavel responsável por controlar os v-if do modal-delete

let urlApi = ref("http://127.0.0.1:8000/api/v1/brand_car");
let fields = reactive({ id: "", name: "" });
let table = ref({
  titles: { id: "Identificador", name: "Marca", image: "Logo" },
  data: dataBrands,
});

//recebido do componente filho por emit
function receivedId($event) {
  fields.id = $event;
  searchBrandApi();
}

//recebido do componente filho por emit
function receivedBrandName($event) {
  fields.name = $event;
  searchBrandApi();
}

//metodo para o switch mostrar todos os registros disparado por emit
function receivedSelectAll($event) {
  retrieveBrandApi(urlApi.value);
}

//recupera as marcas na api para popular a variavel recebida na table
async function retrieveBrandApi(url) {
  const { dataApi, error } = await useFetchApi(url);
  dataBrands.data = dataApi.data;
  dataBrands.configPag = dataApi.configPag;
}

//metodo que cria dados na api disparado por emit do modal create
async function createBrand(form) {
  const { alertCreate } = await createData(urlApi, form); //composable para criação de dados
  brandAlertCreate.data = alertCreate;
  //composable que recarrega página se status for SUCCESS
  reloadApplication(alertCreate.status);
}

//metodo que cria dados na api disparado por emit do modal create
async function updateBrand(form) {
  const { alertUpdate } = await updateData(urlApi, form); //composable para criação de dados
  brandAlertUpdate.data = alertUpdate;
  //composable que recarrega página se status for SUCCESS
  reloadApplication(alertUpdate.status);
}

//disparado por emit do modal create que cria ou atualiza os dados
function createORupdate(form) {
  //se a variavel da store que contém o dado clicado para edição estiver nulo
  if (!store.dataToUpdate) {
    //é criação de dados
    createBrand(form);
  } else {
    //é atualização de dados
    updateBrand(form);
  }
}

//responsável por popular a variavel que irá mostrar um unico registro para visualização
async function retrieveOneBrand(id) {
  const url = `${urlApi.value}/${id}`;
  const { dataFound } = await searchOneData(url);
  brandShow.data = dataFound.value;
}

//metodo disparado por emit do modalShow para limpar a variavel pois foi fechado
function cleanBrand(evemt) {
  brandShow.data = {};
}

//limpa os inputs por template ref
async function cleanInputs(){
    //codigo abaixo faz o mesmo que por exemplo : clean1.value.typedText = ''
    for(let i=1;i<=2;i++){
      await eval(`clean${i}.value.typedText=''`)
    }

    dataBrands.data = [];
    dataBrands.configPag = {};
}

//metodo principal para busca
//pesquisa por letra digitada em algum campo
function searchBrandApi() {
  //a composable formedUrl traz a url formatado com a query dos inputs digitados
  //se retornar vazio é pq os inputs estão vazios
  const { url } = formedUrl(fields, urlApi);
  //se houver consulta valida o metodo que chama a api é invocado
  if (url !== "") {
    return retrieveBrandApi(url);
  }
  //limpo os dados do objeto que renderiza a tabela para ela desaparecer
  return (dataBrands.data = []);
}

//deleta da api pelo click no modal-delete e id da variavel clicada recebida do componente table
async function deleteBrand() {
  const { alert } = await deleteApi(urlApi.value);
  brandAlertDelete.data = alert;

  //altera o template do modal-delete e recarrega a página
  brandDelete.value = alert.status == "SUCCESS" ? true : false;
  reloadApplication(alert.status);
}
</script>

<style>
</style>
