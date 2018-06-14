function createGameTrendChart(id, pieData) {

    
    pieData.datasets[0].backgroundColor = [
        "#5cb85c",
        "#333333",
        "#f0ad4e",
    ];
    
    // For a pie chart
    let ctx = document.getElementById(id).getContext('2d');
    let myPieChart = new Chart(ctx,{
        type: 'pie',
        data: pieData,
        options: {}
    });
}