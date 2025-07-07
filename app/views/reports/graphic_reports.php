<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Distribución de Productos por Categoría</h5>
    </div>
    <div class="card-body">
        <canvas id="productsByCategoryChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Movimientos por Tipo</h5>
    </div>
    <div class="card-body">
        <canvas id="movementsByTypeChart"></canvas>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script para los datos y configuración de los gráficos -->
<script>
    // Datos pasados desde el controlador
    const productsByCategoryData = <?php echo json_encode($data_for_pie_chart ?? []); ?>;
    const movementsByTypesData = <?php echo json_encode($data_for_bar_chart ?? []); ?>;

    // Gráfico de Torta: Productos por Categoría
    if (productsByCategoryData.length > 0) {
        const categories = productsByCategoryData.map(item => item.categoria || 'Sin Categoría');
        const totals = productsByCategoryData.map(item => item.total);

        const ctxPie = document.getElementById('productsByCategoryChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: categories,
                datasets: [{
                    data: totals,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Productos por Categoría'
                    }
                }
            }
        });
    } else {
        document.getElementById('productsByCategoryChart').parentElement.innerHTML = '<div class="alert alert-info text-center">No hay datos de categorías para mostrar.</div>';
    }

    // Gráfico de Barras: Movimientos por Tipo
    if (movementsByTypesData.length > 0) {
        const types = movementsByTypesData.map(item => item.tipo === 'entrada' ? 'Entradas' : 'Salidas');
        const totals = movementsByTypesData.map(item => item.total);

        const ctxBar = document.getElementById('movementsByTypeChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: types,
                datasets: [{
                    label: 'Cantidad de Movimientos',
                    data: totals,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.7)', // Success for Entradas
                        'rgba(231, 76, 60, 0.7)'  // Danger for Salidas
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(231, 76, 60, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    },
                    title: {
                        display: true,
                        text: 'Movimientos de Inventario por Tipo'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Número de Movimientos'
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('movementsByTypeChart').parentElement.innerHTML = '<div class="alert alert-info text-center">No hay datos de movimientos para mostrar.</div>';
    }
</script>
