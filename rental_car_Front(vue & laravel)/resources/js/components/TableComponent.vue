<template>
  <div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th
            scope="col"
            v-for="(title, index) in props.table.titles"
            :key="index"
          >
            {{ title }}
          </th>

          <th></th>

        </tr>
      </thead>
      <tbody>
        <tr v-for="(dataObject, index) in dataInTitles" :key="index">
          <td v-for="(data, key) in dataObject" :key="key">
            <div v-if="key == 'image'">
              <img :src="'/storage/' + data" height="25" alt="logo" />
            </div>
            <div v-else>{{ data }}</div>
          </td>
          <td>
            <div class="d-flex justify-content-around">
              <i
                role="button"
                @click="searchApiData(dataObject)"
                data-bs-target="#modalShow"
                data-bs-toggle="modal"
                class="bi bi-eye-fill text-primary"
              ></i>
              <i
                role="button"
                @click="updateApiData(dataObject)"
                data-bs-target="#modalCreate"
                data-bs-toggle="modal"
                class="bi bi-pencil-fill text-success"
              ></i>
              <i
                role="button"
                @click="deleteApiData(dataObject)"
                data-bs-target="#modalDelete"
                data-bs-toggle="modal"
                class="bi bi-trash-fill text-danger"
              ></i>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <pagination-component
      v-if="showPaginate"
      class="d-flex justify-content-center"
    >
      <li
        v-for="(pagination, index) in props.table.data.configPag"
        :key="index"
        :class="
          pagination.active //se houver pinta de azul
            ? 'active page-item'
            : 'page-item' && pagination.url == null //se for null fica cinza
            ? 'disabled '
            : 'page-item'
        "
        @click="callPagination(pagination.url)"
      >
        <a class="page-link" v-html="pagination.label"></a>
      </li>
    </pagination-component>
  </div>
</template>

<script setup >
import {
  computed,
  defineAsyncComponent,
  onBeforeMount,
  onBeforeUnmount,
  onBeforeUpdate,
  onMounted,
  onUnmounted,
  onUpdated,
} from "vue";
import { useGeralStore } from "../store/dataStore";
import { storeToRefs } from "pinia";

const store = useGeralStore()
const props = defineProps(["table"]);
const emit = defineEmits(["updatePage", "searchDataApi",]);

//metodo computado para exibir os dados de acordo com os titulos da tabela passados
//isso foi feito na intenção de deixar o component mais independente possivel
let dataInTitles = computed(() => {
  //recupero os titulos
  let title = Object.keys(props.table.titles);
  //recuperp os dados
  let data = props.table.data.data;

  //percorre os dados e dentro percorro os titulos para criar um novo objeto com as posições dos titulos.Ex:matche[id]=x[id].Lembrando que objetos permitem sua criação com se fossem arrays
  let matches = data.map((x) => {
    let matche = {};
    title.forEach((element) => {
      matche[element] = x[element];
    });

    return matche;
  });

  return matches;
});

//propriedade computada para mostrar a paginação
//se o array for maior que 3  significa que é há mais páginas
//a api retorna um objeto contendo a posição do previous no primeiro indice do array, o next no último e entre eles o número de páginas
//ou seja se houver 3 posições significa que é uma pagina somente então não há necessidade de mostrar o componente
let showPaginate = computed(() => {
  if (props.table.data.configPag.length > 3) {
    return true;
  }
  return false;
});

function callPagination(page) {
  //se for nulo então acabou as paginas
  if (page != null) {
    emit("updatePage", page);
  }
}

//disparar um emit para o pai para procurar o registro clicado
function searchApiData(data) {
  emit("searchDataApi", data.id);
}

//guarda o id do dado clicado na Store para o componente pai usar para exclusão
function deleteApiData(data) {
    store.idDelete= data.id
}

//persiste na store o objeto clicado com base nos dados props que estão mais completos
function updateApiData(dataIncomplete) {
    let dataComplete = props.table.data.data.filter((x)=>x.id==dataIncomplete.id)[0]
    store.saveDataStore(dataComplete)
}
</script>

<style>
</style>
