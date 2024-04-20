import { ref } from "vue";

export async function createData(url, form) {
    const alertCreate = {}

    let config = {
        headers: {
            /* parametro para o requisiçaõ ser do tipo form-data para arquivos */
            "Content-Type": "multipart/form-data",
            /* parametro para dizer que aceita somente json */
            Accept: "application/json",
        },
    };

    /*axios recebe no metodo post a url o conteudo a
   ser enviado e a configuraçaõ do cabeçalho*/
    await axios
        .post(url.value, form, config)
        .then((ok) => {

            alertCreate.status = 'SUCCESS'
            alertCreate.color = "alert-success";
            alertCreate.title = "Sucesso ao persistir dado!";
            alertCreate.message = ok.data
            alertCreate.show = true;
        })
        .catch((error) => {
            alertCreate.status = 'ERROR'
            alertCreate.color = "alert-danger";
            alertCreate.title = "Erro ao persistir dados!";
            alertCreate.message = error.response.data.errors;
            alertCreate.show = true;
        });

return {alertCreate}
}
