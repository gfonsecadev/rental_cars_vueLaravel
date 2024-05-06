//pesquisa por letra digitada em algum campo
export function formedUrl(fields,urlApi) {
  //se o campo identificar estiver vazio usará somente um filtro para o nome da marca

  let urlFilter = "";
  for (let field in fields) {
    //se for o primeiro laço e o objeto nesta posição não estiver vazio
    //Será adicionado o inicio da query de consulta
    if (urlFilter == "" && fields[field] != "") {
      urlFilter = "?filter=";
    } else if (fields[field] != "") {
      //se o objeto nesta posiçaõ estiver preenchido receberá um ; para formar outra consulta para a query(na construção da api utilizei o ; como caracter de separação para o explode do php separar por consulta where)
      urlFilter += ";";
    }

    if (fields[field] != "") {
      urlFilter += field + "*like*" + fields[field] + "%";
    }else{
        urlFilter
    }
  }

  //se a url de filtro estiver vazia é pq os inputs estão vazios
  //por isso retorno uma string vazia.
  let url = urlFilter !=="" ? urlApi.value + urlFilter : "";

 return {url}
}
