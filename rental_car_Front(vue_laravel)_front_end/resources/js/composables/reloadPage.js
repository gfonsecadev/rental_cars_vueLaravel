export  function reloadApplication(status){

    if(status == 'SUCCESS'){
        setTimeout(()=>{
            location.reload()
        },1000)
    }
}
