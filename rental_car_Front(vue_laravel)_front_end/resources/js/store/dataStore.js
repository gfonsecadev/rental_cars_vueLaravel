import { useDateFormat } from '@vueuse/core'
import { defineStore } from 'pinia'
import { computed, ref, reactive} from 'vue'


//utilizando Composition Api
export const useGeralStore = defineStore('geral', () => {
    //o state é substituido por ref ou reactive
    const idDelete = ref(0)
    const dataUpdate = reactive({data:null})

    //os getters são os computeds
    //recupera a marca clicada(variavel brand) e filtra do objeto brands que contém todos os dados das marcas o que tem o mesmo id que foi clicado
    const dataToUpdate = computed(()=>{
        return dataUpdate.data
    })



    //as actions são funções
    //salva a marca clicada para visulização com dados imcompletos
    function saveDataStore(data) {
        dataUpdate.data=data
    }



    //aqui retornamos o que interessa da store
    return { dataToUpdate, saveDataStore, idDelete }
})


/*
//utilizando Option Api
export const  useBrandStore = defineStore('brand', {
    state: () => {
        return {
            brand: {}
        }
    },
    getters: {
        getBrand(){
            return this.brand
        }
    },
    actions: {
        myAction(data){
         this.brand = data
        }
    }
})
*/
