// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Bar Chart Example
// var ctx = document.getElementById("myBarChart");
// var myBarChart = new Chart(ctx, {
//   type: 'horizontalBar', // Tipo de gráfico de barras
//   data: {
//     labels: nombresProductosMinimo, // Las categorías (productos) en el eje Y
//     datasets: [
//       {
//         label: "Existencias",
//         backgroundColor: "rgba(126, 244, 91, 0.8)",
//         borderColor: "rgba(0, 255, 0, 0.6)",
//         data: existencias, // Valores para las barras (eje X)
//       },
//       {
//         label: "Stock Mínimo",
//         backgroundColor: "rgba(255, 99, 132, 0.2)",
//         borderColor: "rgba(2, 117, 216, 1)",
//         data: stockMinimo, // Valores para las barras (eje X)
//       }
//     ],
//   },
//   options: {
//     indexAxis: 'y', // Cambia la orientación del gráfico a horizontal
//     scales: {
//       x: {
//         stacked: true, // Opcional: apila las barras si hay múltiples datasets
//         ticks: {
//           min: 0,
//           maxTicksLimit: 5
//         },
//         grid: {
//           display: true
//         }
//       },
//       y: {
//         stacked: true, // Opcional: apila las barras si hay múltiples datasets
//         grid: {
//           display: false
//         },
//         ticks: {
//           maxTicksLimit: 6
//         }
//       }
//     },
//     plugins: {
//       legend: {
//         display: true // Muestra la leyenda
//       }
//     }
//   }
// });

var ctx = document.getElementById('myBarChart').getContext('2d');

// Datos del gráfico (ajusta estos valores según tus datos)
var data = {
    labels: nombresProductosMinimo,
    datasets: [{
        label: 'Existencias',
        data: existencias,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }, {
        label: 'Stock Mínimo',
        data: stockMinimo,
        backgroundColor: 'rgba(255, 99, 132, 0.2)', // Cambiado para que también sea una barra
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 1
    }]
};

// Configuración del gráfico
var options = {
    scales: {
        x: {
            beginAtZero: true,
            stacked: true // Habilitar el apilado en el eje X
        },
        y: {
            stacked: true // Habilitar el apilado en el eje Y
        }
    }
};

// Crear el gráfico
var myChart = new Chart(ctx, {
    type: 'horizontalBar', // Cambiar a tipo "bar" en lugar de "horizontalBar" ya que Chart.js 3+ usa "bar" para ambos
    data: data,
    options: options
});





