<template>
  <div
    id="carouselExampleControlsNoTouching"
    class="carousel slide"
    data-bs-touch="false"
  >
    <div class="'carousel-inner">
      <div
        v-for="(data, index) in dataApi.data"
        :key="index"
        :class="'carousel-item ' + returnClass(index)"
      >
        <div class="card">
          <div class="card-header d-flex justify-content-center">
            <img :src="'storage/' + data.image" :alt="data.name" />
          </div>
          <div class="card-body row">
            <span class="card-text  col-12 col-md-4 col-lg-6 col-xd-2"
              ><strong>Marca:</strong> {{ data.brand.name }}</span
            >
            <span class="card-text col-12 col-md-4 col-lg-6 col-xd-2"
              ><strong>Modelo:</strong> {{ data.name }}</span
            >
            <span class="card-text col-12 col-md-4 col-lg-6 col-xd-2"
              ><strong>Número de assentos :</strong> {{ data.seats }}</span
            >
            <span class="card-text col-12 col-md-4 col-lg-6 col-xd-2"
              ><strong>Número de portas:</strong> {{ data.number_doors }}</span
            >
            <span class="card-text col-12 col-md-4 col-lg-6 col-xd-2"
              ><strong>Airbag:</strong> {{ convert(data.air_bag) }}</span
            >
            <span class="card-text col-12 col-md-4 col-lg-6 col-xd-2"
              ><strong>Abs:</strong> {{ convert(data.abs)  }}</span
            >
          </div>
        </div>
      </div>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#carouselExampleControlsNoTouching"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#carouselExampleControlsNoTouching"
      data-bs-slide="next"
    >
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</template>

<script setup>
import { useFetchApi } from "../composables/searchApi";
const urlApi = "http://127.0.0.1:8000/api/v1/model_car?relationship=name,image";
//contém os dados para popular o carousel
const { dataApi } = await useFetchApi(urlApi);

//função para inserir a classe active no primeiro carousel-item  no carrossel para o Bootstrap funcionar corretamente
function returnClass(data) {
  if (data == 0) {
    return "active";
  }
}

//converte 1 e 0 para sim e não
function convert(data) {
  if (data == 1) {
    return "Sim";
  } else {
    return "Não";
  }
}
</script>

<style scoped>
.carousel,
.carousel-item {
  height: 70vh;
}
.carousel img {
  max-height: 500px;
  max-width: 80%;
}

.card {
  width: 50%;
  height: 100%;
  margin: auto;
  font-size: 1rem;
}
</style>
