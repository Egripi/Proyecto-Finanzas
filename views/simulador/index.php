<div class="simulador">
  <?php  include_once __DIR__ . '/../templates/sidebar.php' ?>
  <!-- Enlace al js -->
    <script src="build/js/app.js"></script>

  <div class="principal">
    <?php include_once __DIR__ . '/../templates/barra.php' ?>

    <div class="contenido">
      <h2 class="nombre-pagina"><?php echo $titulo; ?></h2>


        <div class="container-calculadora">
            <form>
                <h1>Calculadora Credito Vechicular</h1>

                <label for="Precio-Vehiculo">Precio Vehiculo</label>
                <input class="PrecioVehiculo" type="number" name="Precio-Vehiculo" id="Precio-Vehiculo" placeholder="S/.">

                <label for="TEA">TEA</label>
                <input class="ValorTEA" type="number" name="TEA" id="TEA" placeholder="%">

                <label for="Ingresos">Ingresos</label>
                <input class="Ingresos" type="number" name="Ingresos" id="Ingresos" placeholder="S/.">

                <label for="Anios">Años</label>
                <input class="Anios" type="number" name="Anios" id="Anios" placeholder="Minimo 1 año">
                
                <label for="1era-cuota">1era Cuota:</label>
                <input class="Valor1eraCuota" type="number" name="1era-cuota" id="1era-cuota" placeholder="S/.">

                <button class="btnCalcular" id="Calcular" type="button">Calcular</button>
            </form>

            <div class="resultados">
                <h2>Resultados:</h2>
                <label for="precio-cuota">Precio Cuota:</label>
                <textarea name="precio-cuota" id="precio-cuota" cols="20" rows="1"readonly></textarea>

                <label for="Monto-total">Monto Total:</label>
                <textarea name="Monto-total" id="Monto-total" cols="20" rows="1" readonly></textarea>

                <label for="Intereses">Intereses:</label>
                <textarea name="Intereses" id="Intereses" cols="20" rows="1" readonly></textarea>

                <label for="VAN">VAN:</label>
                <textarea name="VAN" id="VAN" cols="20" rows="1" readonly></textarea>

                <label for="TIR">TIR:</label>
                <textarea name="TIR" id="TIR" cols="20" rows="1" readonly></textarea>

            </div>

        </div>
    </div>
  </div>
</div>

<?php
$script = '
  <script src="buid/js/app.js"></script> 
'
?>