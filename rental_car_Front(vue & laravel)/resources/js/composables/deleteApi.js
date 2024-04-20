import axios from "axios";
import { useGeralStore } from "../store/dataStore";

//composable para exclusão de dados da api
export async function deleteApi(url) {
    const alert = {}

    const store = useGeralStore()
    const urlApi = `${url}/${store.idDelete}`
    const cookie = document.cookie.split(";")[0].replace("token=", "Bearer ")

    const dataForm = new FormData()
    dataForm.append('_method', 'delete')

    let config = {
        headers: {
            /* parametro para dizer que aceita somente json */
            Accept: "application/json",
            //não é preciso no meu caso enviar token pois meus cookies estão funcionando, mas se precisa-se faria da forma abaixo
            Authorization: cookie
        }
    }


    await axios.post(urlApi, dataForm, config)
        .then((ok) => {
            alert.status = "SUCCESS"
            alert.color = "alert-success";
            alert.title = "Sucesso ao deletar dados!";
            alert.message = ok.data;
            alert.show = true;
        }).catch((error) => {
            alert.status = "ERROR"
            alert.color = "alert-danger";
            alert.title = "Erro ao deletar dados!";
            alert.message = error.response.data;
            alert.show = true;
        })


    return { alert }
}
