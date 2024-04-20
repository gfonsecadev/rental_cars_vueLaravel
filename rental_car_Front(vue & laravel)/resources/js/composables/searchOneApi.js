import axios from "axios";
import { ref } from "vue";
//composable para busca de um único arquivo para exibição
export async function searchOneData(url){
    const dataFound = ref({})

    //essa pesquisa utiliza o rota get xxx/id da api para trazer um único arquivo
    await axios.get(url).then(({data})=>{
        dataFound.value= data
    })


    return {dataFound}
}
