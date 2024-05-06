<template>
  <div class="form-group">
    <label :for="props.labelFor">{{ props.labelTitle }}</label>
    <!-- se contiver inputType nas props é um input -->
    <input required
      ref="input"
      v-if="inputAccept.includes(props.inputType)"
      :type="props.inputType"
      :class="props.inputStyle"
      :id="props.inputId"
      :placeholder="props.inputPlaceholder"
      @keyup="updatePress()"
      @change="updateChange($event)"
      v-model="typedText"
    />

    <!-- se não for um input do tipo aceito é pq é um select -->
    <select required :class="props.inputStyle" v-else v-model="typedText" @change="updateChange($event)">
        <option value="" selected disabled hidden >{{props.optionDefault}}</option>
      <option
        v-for="(option, index) in props.options"
        :value="option"
        :key="index"
      >
        {{ index}}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref } from "vue";
//fazendo um template ref para que o input possa ser controlado aqui ou em outro componente pois estou exportando-o
const input = ref();
//array que contém os tipos de inputs aceitos.
const inputAccept = ['text', 'number', 'file', 'checkbox']

const props = defineProps([
  "labelTitle",
  "labelFor",
  "inputType",
  "inputId",
  "inputStyle",
  "inputPlaceholder",
  "options",
  "optionDefault"
]);
const typedText = ref(""); //variavel que faz vinculo com o input
const selectedFile = ref(null); //variavel que faz vinculo com o input do tipo file

//expondo as variaveis para o componente que implememtar o template ref possa ter controle sobre elas
defineExpose({ typedText, selectedFile, input });

//crie seu emit
//um emit para inputs comuns e outro para tipo file
const emit = defineEmits(["out", "outFile", "outSwitch", "outOption"]);

//crie uma função que receberá a variavel criada para armazenar os emits
//neste caso o metodo update será chamado no evendo keyup do input
//então passe o nome do emit e o que ele passará para o componente pai
function updatePress() {
  //se for tipo normal(text,number, etc...)
  emit("out", typedText.value);
}

//para inputs alterados pelo evento change
function updateChange($event) {
  //se for um arquivo terá duas formas de recuperaçao dos dados
  if ($event.target.files) {
    let file = $event.target.files;
    //envio por template ref
    selectedFile.value = $event.target.files;
    //envio por emit
    emit("outFile", file);
  } else {
    //se for um switch
    emit("outSwitch", typedText.value);
    //se for option do select
    emit("outOption", $event.target.value);
  }
}
</script>

<style >
</style>
