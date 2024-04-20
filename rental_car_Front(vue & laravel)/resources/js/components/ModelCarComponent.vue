<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between">
            <h3>Modelos de carros</h3>
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
                  label-for="modelId"
                  label-title="Identificador"
                  input-type="number"
                  input-id="modelId"
                  input-placeholder="Insira um identificador de um modelo"
                >
                </field-create-component>

                <field-create-component
                  ref="clean2"
                  @out="receivedModelName($event)"
                  class="col-6"
                  input-style="form-control"
                  label-for="modelName"
                  label-title="Nome do modelo"
                  input-type="text"
                  input-id="modelName"
                  input-placeholder="Insira um nome de uma modelo"
                >
                </field-create-component>

              </div>

              <div class="row mt-3">
                <field-create-component
                  ref="clean3"
                  @outOption="receivedModelAirBag($event)"
                  class="col-6"
                  input-style="form-control"
                  label-for="modalAirBag"
                  label-title="Tem airbag ?"
                  :options ="selectOptionsDefault.data"
                  optionDefault="Selecione Sim ou Não"
                  input-id="modelAirBag"
                >
                </field-create-component>

                <field-create-component
                   ref="clean4"
                  @outOption="receivedModelAbs($event)"
                  class="col-6"
                  input-style="form-control"
                  label-for="modelName"
                  label-title="Tem abs ?"
                  option-default ="Selecione Sim ou Não"
                  :options ="selectOptionsDefault.data"
                  input-id="modelBrand"

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
                v-if="dataModels.data == ''"
                @outSwitch="receivedSelectAll($event)"
                input-style="form-check-input"
                label-for="showAll"
                label-title="Mostrar todas os modelos disponíveis"
                input-type="checkbox"
                input-id="modelId"
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
        @updatePage="retrieveModelApi($event)"
        @searchDataApi="retrieveOneModel($event)"
        v-if="dataModels.data.length > 0"
        :table="table"
      >
      </table-component>
    </transition-group>

    <!-- será chamado pelo click na tag a aqui na linha 11 -->
    <modal-save-component
      @createData="createORupdate($event)"
      :title="modelTitle"
      :data-create-label="modelCreateLabels"
      :alert-data-create="modelAlertCreate"
      :alert-data-update="modelAlertUpdate"
    >
    </modal-save-component>

    <!-- será chamado pelo click na tag i no componente table linha 25 -->
    <modal-show-component
      @cleanData="cleanModel($event)"
      :data-show="modelShow"
    ></modal-show-component>

    <modal-delete-component
      @deleteData="deleteModel()"
      :alert-data="modelAlertDelete"
      :success="modelDelete"
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
const clean3 = ref();
const clean4 = ref();

//chamando a store
const store = useGeralStore();

//objeto que popula a tabela
let dataModels = reactive({ data: [], configPag: {} });

//para popular os selects(para aproveitar a estrutura do select todos os dados foram formatados como na primeira linha a seguir)
let selectOptionsDefault = reactive({data:{"Sim": 1, "Não": 0}});//dentro do data um objeto com chaves que serão o label do option e o valor que será o seu value
let selectOptionsBrands = reactive({data:{}});//também será formatado no onMounted para aproveitar a estrutura do select

//popular os rotulos e os dados do componente de vizualização
let modelShow = reactive({
  labels: {
    id: "Identificador",
    name: "Nome",
    brand: "Nome da marca",
    number_doors: "Qtd de portas",
    abs: "Abs",
    air_bag: "Airbag",
    seats: "Quantidade de assentos",
    created_at: "Data de criação",
    updated_at: "Data de atualização",},
  data: {},
});

//popular os rotulos e o tipo dos inputs do componente de criação
let modelCreateLabels = reactive({
  Nome: ["name", "text", "Insira o nome do modelo"],
  Marca: ["brand_id", selectOptionsBrands, "Selecione uma marca"],
  Logo: ["image", "file", "Insira uma imagem"],
  "Quantidade de portas": ["number_doors","number", "Insira a quantidade de portas"],
  "Quantidade de assentos": ["seats","number", "Insira a quantidade de assento"],
  "Airbag": ["air_bag", selectOptionsDefault, "Esse modelo possui airbag ?"],
  "Abs": ["abs", selectOptionsDefault, "Esse modelo possui abs ?"]

});//Propriedade computada do titulo do modal-create
let modelTitle = computed(()=>{
    if(!store.dataToUpdate){
        return "Formulário de criação de modelo"
    }else{
        return "Formulário de atualização de modelo"
    }
})

//para popular os alerts
let modelAlertCreate = reactive({ data: {} });
let modelAlertUpdate = reactive({ data: {} });
let modelAlertDelete = reactive({ data: {} });
let modelDelete = ref(false); //variavel responsável por controlar os v-if do modal-delete

//url principal da api
let urlApi = ref("http://127.0.0.1:8000/api/v1/model_car");

//variavéis para receber os emits.
let fields = reactive({ id: "", name: "" , air_bag: "", abs: ""});

//para popular a tabela
let table = ref({
  titles: { id: "Identificador", name: "Modelo", image: "Logo" },
  data: dataModels,
});

//recebido do componente filho por emit
function receivedId($event) {
  fields.id = $event;
  searchModelApi();
}

//recebido do componente filho por emit()
function receivedModelName($event) {
  fields.name = $event;
  searchModelApi();
}

//recebido do componente filho por emit de um select
function receivedModelAbs($event){
    fields.abs = $event;
    searchModelApi();

}

function receivedModelAirBag($event){
    fields.air_bag = $event
    searchModelApi();
}

//metodo para o switch mostrar todos os registros disparado por emit
async function receivedSelectAll($event) {
  cleanInputs()
  retrieveModelApi(urlApi.value);
}

//recupera as marcas na api para popular a variavel recebida na table
async function retrieveModelApi(url) {
  const { dataApi, error } = await useFetchApi(url);
  dataModels.data = dataApi.data;
  dataModels.configPag = dataApi.configPag;
}

//metodo que cria dados na api disparado por emit do modal create
async function createModel(form) {
  const { alertCreate } = await createData(urlApi, form); //composable para criação de dados
  modelAlertCreate.data = alertCreate;
  //composable que recarrega página se status for SUCCESS
  reloadApplication(alertCreate.status);
}

//metodo que cria dados na api disparado por emit do modal create
async function updateModel(form) {
  const { alertUpdate } = await updateData(urlApi, form); //composable para criação de dados
  modelAlertUpdate.data = alertUpdate;
  //composable que recarrega página se status for SUCCESS
  reloadApplication(alertUpdate.status);
}

//disparado por emit do modal create que cria ou atualiza os dados
function createORupdate(form) {

  //se a variavel da store que contém o dado clicado para edição estiver nulo
  if (!store.dataToUpdate) {
    //é criação de dados
    createModel(form);
  } else {
    //é atualização de dados
    updateModel(form);
  }
}

//responsável por popular a variavel que irá mostrar um unico registro para visualização
async function retrieveOneModel(id) {
  const url = `${urlApi.value}/${id}`;
  const { dataFound } = await searchOneData(url);
  modelShow.data = dataFound.value;
}

//metodo disparado por emit do modalShow para limpar a variavel pois foi fechado
function cleanModel(evemt) {
  modelShow.data = {};
}

//limpa os inputs por template ref
async function cleanInputs(){
    //codigo abaixo faz o mesmo que por exemplo : clean1.value.typedText = ''
    for(let i=1;i<=4;i++){
      await eval(`clean${i}.value.typedText=''`)
    }

    dataModels.data = [];
    dataModels.configPag = {};
}

//metodo principal para busca
//pesquisa por letra digitada em algum campo
function searchModelApi() {
  //a composable formedUrl traz a url formatado com a query dos inputs digitados
  //se retornar vazio é pq os inputs estão vazios
  const { url } = formedUrl(fields, urlApi);
  //se houver consulta valida o metodo que chama a api é invocado
  if (url !== "") {
    return retrieveModelApi(url);
  }
  //limpo os dados do objeto que renderiza a tabela para ela desaparecer
  return (dataModels.data = []);
}

//deleta da api pelo click no modal-delete e id da variavel clicada recebida do componente table
async function deleteModel() {
  const { alert } = await deleteApi(urlApi.value);
  modelAlertDelete.data = alert;

  //altera o template do modal-delete e recarrega a página
  modelDelete.value = alert.status == "SUCCESS" ? true : false;
  reloadApplication(alert.status);
}


//para carregar os options das marcas pro select
onMounted(async ()=>{
    //chamo a rota da api que contém as marcas de carro
    const {dataApi} = await useFetchApi("http://127.0.0.1:8000/api/v1/brand_car?all")
    const newArray= {}
    selectOptionsBrands.data= dataApi.data.map((x)=>{
        newArray[x.name]=x.id;
    });
    selectOptionsBrands.data = newArray


})

</script>

<style>
</style>
