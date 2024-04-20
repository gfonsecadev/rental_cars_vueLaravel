import { ref } from "vue";
import { useGeralStore } from "../store/dataStore";

export async function updateData(url, form) {
    const store = useGeralStore()
    const dataUpdate = store.dataToUpdate

    const alertUpdate = {}
    const urlApi = `${url.value}/${dataUpdate.id}`
   // form.append("id", dataUpdate.id)
    form.append("_method", "patch")
    let config = {
        headers: {
            /* parametro para o requisiçaõ ser do tipo form-data para arquivos */
            "Content-Type": "multipart/form-data",
            /* parametro para dizer que aceita somente json */
            Accept: "application/json",
        },
    };
    console.log(form.get("id"))
    /*axios recebe no metodo post a url o conteudo a
   ser enviado e a configuraçaõ do cabeçalho*/
    await axios
        .post(urlApi, form, config)
        .then((ok) => {
            alertUpdate.status = 'SUCCESS'
            alertUpdate.color = "alert-success";
            alertUpdate.title = "Sucesso ao atualizar dado!";
            alertUpdate.message = ok.data
            alertUpdate.show = true;
        })
        .catch((error) => {
            alertUpdate.status = 'ERROR'
            alertUpdate.color = "alert-danger";
            alertUpdate.title = "Erro ao atualizar dados!";
            alertUpdate.message = error.response.data.errors;
            alertUpdate.show = true;
        });

return {alertUpdate}
}
