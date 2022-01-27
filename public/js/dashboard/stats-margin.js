// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctxAdditionalServices = document.getElementById("myPieChartStatsAdditionalServices");
var ctxMedicals = document.getElementById("myPieChartStatsMedicals");
var ctxMargin = document.getElementById("myPieChartStatsMargin");

var url = '/api/stats-margin';
const headers = new Headers();
headers.append('Accept', 'application/json');
headers.append('Content-Type', 'application/json');

fetch(url, {
  headers: headers,
  method: 'get',
})
.then(response => response.json())
.then(result => {
  //return data;

  var myPieChartStatsAdditionalServices = new Chart(ctxAdditionalServices, {
    type: 'doughnut',
    data: {
      labels: ["Marża dla lekarza", "Marża dla firmy", "Marża suma"],
      datasets: [{
        data: [result.additional_services_margin_doctor_all.toFixed(2), result.additional_services_margin_company_all.toFixed(2), result.additional_services_margin_all.toFixed(2)],
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });

  var myPieChartStatsMedicals = new Chart(ctxMedicals, {
    type: 'doughnut',
    data: {
      labels: ["Marża dla lekarza", "Marża dla firmy", "Marża suma"],
      datasets: [{
        data: [result.medicals_margin_doctor_all.toFixed(2), result.medicals_margin_company_all.toFixed(2), result.medicals_margin_all.toFixed(2)],
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });

  var myPieChartStatsMargin = new Chart(ctxMargin, {
    type: 'doughnut',
    data: {
      labels: ["Marża dla lekarza", "Marża dla firmy", "Marża suma"],
      datasets: [{
        data: [result.margin_doctor.toFixed(2), result.margin_company.toFixed(2), result.margin_all.toFixed(2)],
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
        hoverBorderColor: "rgba(234, 236, 244, 1)",
      }],
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      cutoutPercentage: 80,
    },
  });
})
.catch(error => {
    // handle the error
    console.err(error);
});
