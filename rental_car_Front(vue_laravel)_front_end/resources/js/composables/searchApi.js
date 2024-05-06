import { ref, reactive } from "vue";

//todo a lógica para buscar dados da api.
export async function useFetchApi(url) {
    // console.log(url)
    const dataApi = reactive({
        data: [],
        configPag: {}
    });
    const error = ref()

    //o await irá nos garantir que a variavel será preenchida antes de ser retornada
    await axios.get(url).then((ok) => {
        //ou ok.data.data quando paginado ou ok.data quando tudo de uma vez
        dataApi.data = ok.data.data ?? ok.data
        dataApi.configPag = ok.data.links;
        console.log(dataApi.data,url)
    }).catch((x) => error.value = x);


    return { dataApi, error }


}
