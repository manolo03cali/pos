// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example
// var ctx = document.getElementById("myAreaChart");
// var myLineChart = new Chart(ctx, {
//   type: 'line',
//   data: {
//     //labels: ["Mar 1", "Mar 2", "Mar 3", "Mar 4", "Mar 5", "Mar 6", "Mar 7", "Mar 8", "Mar 9", "Mar 10", "Mar 11", "Mar 12", "Mar 13"],
//     labels:nombresDias,
//     datasets: [{
//       label: "Ventas",
//       lineTension: 0.3,
//       backgroundColor: "rgba(2,117,216,0.2)",
//       borderColor: "rgba(2,117,216,1)",
//       pointRadius: 5,
//       pointBackgroundColor: "rgba(2,117,216,1)",
//       pointBorderColor: "rgba(255,255,255,0.8)",
//       pointHoverRadius: 5,
//       pointHoverBackgroundColor: "rgba(2,117,216,1)",
//       pointHitRadius: 50,
//       pointBorderWidth: 2,
//       //data: [10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984, 38451],
//       data: totales,
//     }],
//   },
//   options: {
//     scales: {
//       xAxes: [{
//         time: {
//           unit: 'date'
//         },
//         gridLines: {
//           display: false
//         },
//         ticks: {
//           maxTicksLimit: 7
//         }
//       }],
//       yAxes: [{
//         ticks: {
//           min: 0,
//           //max: 40000,
//           max: valorMaximo,
//           maxTicksLimit: 5
//         },
//         gridLines: {
//           color: "rgba(0, 0, 0, .125)",
//         }
//       }],
//     },
//     legend: {
//       display: false
//     }
//   }
// });

var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: nombresDias,
    datasets: [{
      label: "Ventas",
      lineTension: 0.2, // Reducir la tensión para suavizar la línea
      backgroundColor: "rgba(0, 200, 83, 0.2)", // Fondo del área bajo la línea (verde con transparencia)
      borderColor: "rgba(0, 200, 83, 1)", // Color del borde de la línea (verde sólido)
      pointRadius: 5, // Tamaño de los puntos
      pointBackgroundColor: "rgba(0, 200, 83, 1)", // Color de fondo de los puntos (verde sólido)
      pointBorderColor: "rgba(255, 255, 255, 0.8)", // Borde de los puntos (blanco con transparencia)
      pointHoverRadius: 7, // Tamaño al pasar el mouse
      pointHoverBackgroundColor: "rgba(0, 255, 132, 1)", // Color del punto al pasar el mouse (verde brillante)
      pointHitRadius: 30, // Área de impacto para clics
      pointBorderWidth: 2, // Ancho del borde de los puntos
      data: totales, // Datos de las ventas
    }],
  },
  options: {
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false // Ocultar líneas de la cuadrícula
        },
        ticks: {
          maxTicksLimit: 7 // Limitar el número de etiquetas en el eje X
        }
      }],
      yAxes: [{
        ticks: {
          min: 0, // Valor mínimo
          max: valorMaximo, // Valor máximo ajustado dinámicamente
          maxTicksLimit: 5 // Limitar el número de etiquetas en el eje Y
        },
        gridLines: {
          color: "rgba(0, 0, 0, .1)", // Color de las líneas de la cuadrícula
          zeroLineColor: "rgba(0, 0, 0, 0.1)" // Color de la línea base (cero)
        }
      }],
    },
    legend: {
      display: true, // Mostrar la leyenda
      labels: {
        fontColor: "#333", // Color de las etiquetas en la leyenda
        fontSize: 14 // Tamaño de fuente de la leyenda
      }
    },
    tooltips: {
      backgroundColor: "rgba(0,0,0,0.7)", // Fondo oscuro para tooltips
      bodyFontColor: "white", // Texto blanco en tooltips
      titleFontColor: "white", // Título blanco en tooltips
      borderColor: "rgba(0, 0, 0, 0.2)", // Borde del tooltip
      borderWidth: 1 // Ancho del borde del tooltip
    },
    animation: {
      duration: 2000, // Duración de la animación en milisegundos
      easing: 'easeInOutBounce', // Efecto de rebote
      onComplete: function() {
        // Opcional: Agregar acciones cuando la animación termine
      }
    }
  }
});