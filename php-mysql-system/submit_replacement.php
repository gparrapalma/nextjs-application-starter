<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rut = $_POST['rut'];
    $fecha_ausencia = $_POST['fecha_ausencia'];
    $fecha_inicio_reemplazo = $_POST['fecha_inicio_reemplazo'];
    $fecha_termino_reemplazo = $_POST['fecha_termino_reemplazo'];

    // Get employee id by rut
    $stmt = $conn->prepare("SELECT id FROM employees WHERE rut = ?");
    $stmt->bind_param("s", $rut);
    $stmt->execute();
    $stmt->bind_result($employee_id);
    if ($stmt->fetch()) {
        $stmt->close();

        // Insert replacement request
        $stmt = $conn->prepare("INSERT INTO replacement_requests (employee_id, fecha_ausencia, fecha_inicio_reemplazo, fecha_termino_reemplazo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $employee_id, $fecha_ausencia, $fecha_inicio_reemplazo, $fecha_termino_reemplazo);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: export_pdf.php?id=" . $conn->insert_id);
            exit();
        } else {
            $error = "Error al guardar la solicitud.";
        }
    } else {
        $error = "Empleado no encontrado.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Guardar Solicitud</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php if (!empty($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
