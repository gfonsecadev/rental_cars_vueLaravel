import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

//window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//intercepta as solicitações da aplicação
axios.interceptors.request.use(
    //Primeira função de callBack recebe a configuração do cabeçalho da requisição
    (config) => {
        //inserimos o tipo de requisição que nossa requisição aceita
        config.headers['Accept'] = 'application/json'

        //procedimento para retirar o token jwt dos cookies
        let token = document.cookie.split(';').find((index) => {
            return index.includes('token=')
        })

        token = token.split('=')[1]
        token = 'Bearer ' + token
        //inserimos o token na requisição
        config.headers.Authorization = token
        //retornamos o config alterado para a aplicação
        return config
    },

    //aqui recuperamos os erros de solicitação
    (error) => {
        return Promise.reject(error)
    }

)

//intercepta as respostas da aplicação
axios.interceptors.response.use(
    //sucesso
    (ok) =>{
 return ok
},
    //erro
    (error) => {
        //console.log(error)
       //se passar é pq o erro é de token expirado
        if (error.response?.status == 401 && error.response?.data.message == 'Unauthenticated.') {
            //chamo a rota que gera um novo token com base no antigo expirado
            axios.post("http://localhost:8000/api/refresh").then((ok) => {
                //anexo o novo token gerado nos cookies
                document.cookie = `token=${ok.data.newToken};Path=/;SameSite=Lax`
                //faço o reload da aplicação
                location.reload()
            }

            )
        }

        return Promise.reject(error)
    })
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
