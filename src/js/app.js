document.addEventListener('DOMContentLoaded', () => {
    // Obtener los elementos del formulario
    const btnCalcular = document.getElementById('Calcular');
    const inputPrecioVehiculo = document.getElementById('Precio-Vehiculo');
    const inputTEA = document.getElementById('TEA');
    const inputIngresos = document.getElementById('Ingresos');
    const inputAnios = document.getElementById('Anios');
    const inputPrimeraCuota = document.getElementById('1era-cuota');
    const textareaPrecioCuota = document.getElementById('precio-cuota');
    const textareaMontoTotal = document.getElementById('Monto-total');
    const textareaIntereses = document.getElementById('Intereses');
    const textareaVAN = document.getElementById('VAN');
    const textareaTIR = document.getElementById('TIR');
    
    // Función para calcular los resultados
    function calcular() {
       const precioVehiculo = parseFloat(inputPrecioVehiculo.value);
       const tea = parseFloat(inputTEA.value) / 100; // Convertir a decimal
       const ingresos = parseFloat(inputIngresos.value);
       const anios = parseInt(inputAnios.value);
       const primeraCuota = parseFloat(inputPrimeraCuota.value);
    
       // Calcular la tasa de interés mensual
       const tem = Math.pow(1 + tea, 1 / 12) - 1;
    
       // Calcular el número de cuotas mensuales
       const numCuotas = anios * 12;
    
       // Calcular la cuota mensual
       const cuotaMensual = (precioVehiculo * tem) / (1 - Math.pow(1 + tem, -numCuotas));
    
       // Calcular el monto total a pagar
       const montoTotal = cuotaMensual * numCuotas;
    
       // Calcular los intereses totales
       const interesesTotales = montoTotal - precioVehiculo;
    
       // Calcular el VAN del préstamo
       const van = -precioVehiculo + (cuotaMensual * (1 - Math.pow(1 + tem, -numCuotas))) / (1 + tea) ** ingresos;
    
       // Calcular el TIR del préstamo
       const tir = Math.pow((1 + tea) / (1 + tem), -numCuotas) - 1;
    
       // Mostrar los resultados en las áreas de texto
       textareaPrecioCuota.value = cuotaMensual.toFixed(2);
       textareaMontoTotal.value = montoTotal.toFixed(2);
       textareaIntereses.value = interesesTotales.toFixed(2);
       textareaVAN.value = ((-1*van)/10).toFixed(2);
       textareaTIR.value = (100 - (tir*-100)).toFixed(2);

    }
    
    // Asociar la función de cálculo al evento de clic en el botón
    btnCalcular.addEventListener('click', calcular);
    });