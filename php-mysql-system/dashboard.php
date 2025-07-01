<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Role-based access control example
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <p>Your role: <?php echo htmlspecialchars($role); ?></p>
    <a href="logout.php">Logout</a>

    <h2>Solicitud de Reemplazo Funcionario</h2>
    <form method="POST" action="submit_replacement.php">
        <label for="rut">RUT:</label><br>
        <input type="text" id="rut" name="rut" required><br><br>

        <div id="employee-info">
            <!-- Employee info will be displayed here after fetching -->
        </div>

        <label for="fecha_ausencia">Fecha de Ausencia:</label><br>
        <input type="date" id="fecha_ausencia" name="fecha_ausencia" required><br><br>

        <label for="fecha_inicio_reemplazo">Fecha Inicio Reemplazo:</label><br>
        <input type="date" id="fecha_inicio_reemplazo" name="fecha_inicio_reemplazo" required><br><br>

        <label for="fecha_termino_reemplazo">Fecha Termino Reemplazo:</label><br>
        <input type="date" id="fecha_termino_reemplazo" name="fecha_termino_reemplazo" required><br><br>

        <button type="submit">Guardar Solicitud</button>
    </form>

    <script>
        document.getElementById('rut').addEventListener('blur', function() {
            var rut = this.value;
            if (rut) {
                fetch('fetch_employee.php?rut=' + encodeURIComponent(rut))
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            document.getElementById('employee-info').innerHTML = '<p style="color:red;">' + data.error + '</p>';
                        } else {
                            document.getElementById('employee-info').innerHTML = 
                                '<p>Nombre: ' + data.nombre + '</p>' +
                                '<p>Planta: ' + data.planta + '</p>' +
                                '<p>Turno: ' + data.turno + '</p>' +
                                '<p>Grado: ' + data.grado + '</p>';
                        }
                    });
            } else {
                document.getElementById('employee-info').innerHTML = '';
            }
        });
    </script>
</body>
</html>
