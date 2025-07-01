<?php
require_once 'db.php';

if (isset($_GET['rut'])) {
    $rut = $_GET['rut'];

    $stmt = $conn->prepare("SELECT nombre, planta, turno, grado FROM employees WHERE rut = ?");
    $stmt->bind_param("s", $rut);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Empleado no encontrado']);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'RUT no proporcionado']);
}
?>
