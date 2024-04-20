.<template>
  <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card ">
                <div class="card-header">Olá, {{props.userName}} !</div>
                <div class="card-body text-center">
                  <h3 > Bem vindo a RentalCars Brasil.</h3>
                  <p >Aqui você encontra o veiculo que precisa</p>
                  <p>Fique a vontade para pesquisar marcas, modelos de carros, basta acessar omenu veículos acima</p>
                </div>
            </div>
        </div>

    </div>
</div>
</template>

<script setup>
const props= defineProps(['userName'])

</script>

<style scoped>
 .card{
  background: linear-gradient(to right bottom, rgb(101, 94, 94),white) no-repeat ;
  box-shadow: 0 0 20px 10px ;
  animation: pulse 2s infinite;
 }
</style>

<style>
  .card{
  background: linear-gradient(to right bottom, rgb(101, 94, 94),white) no-repeat ;
  box-shadow: 0 0 20px 10px ;
  }
</style>
