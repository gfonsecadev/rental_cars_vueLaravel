<template>
    <div class="container">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Login</div>

                <div class="card-body">
                    <form method="POST" action="" @submit.prevent="login($event)">
                        <input type="hidden" name="_token" :value="props.token_csrf">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Endereço de email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" v-model="data.email" value="" required autocomplete="email" autofocus>


                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Senha</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" v-model="data.password" required autocomplete="current-password">

                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                    <label class="form-check-label" for="remember">
                                        Lembre-me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Entrar
                                </button>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</template>


<script setup>
import { reactive, ref } from "vue"

    /*todas props são armazenadas nesta variável e
     para serem utilizadas basta usar props.nome_da_prop_recebida */
    const props=defineProps(['token_csrf'])

    //crio um objeto que será um vue-model com os inputs
    const data=reactive({email:'gilmar@gmail.com',password:'1234'})

    //metodo para disparar o formulario com a anexação do token Jwt nos cokies
   function login(e){
        let url='http://localhost:8000/api/login'
        let config= {
            method: 'post',
            //UrlSearchParams ajudará o fetch a processar esses objeto como parametros de url
            body:new URLSearchParams( {
                email: data.email,
                password: data.password
            })
        }

        //requisição para a api de locação de veiculos
        //para recebimento do token jwt se credenciais enviadas baterem.
        fetch(url,config)
                       .then((resp)=>resp.json())
                       .then((resp)=>{
                        if(resp.token){
                            document.cookie = 'token=' + resp.token + ';SameSite=Lax'
                        }
                        e.target.submit()

                       })
    }
</script>
