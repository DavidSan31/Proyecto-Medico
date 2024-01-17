<?php
include('conexion.php');

if (isset($_POST['name'], $_POST['email'], $_POST['pswd'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['pswd'];

    // Verificar que $password no sea nulo y tenga un valor
    if ($password !== null && $password !== "") {
        try {
            $stmt = $conn->prepare("SELECT `nombre` FROM `datos` WHERE `nombre` = :name");
            $stmt->execute(['name' => $name]);

            $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($nameExist)) {
                $conn->beginTransaction();

                $insertStmt = $conn->prepare("INSERT INTO `datos` (`nombre`, `email`, `pswd`) VALUES (:nombre, :email, :pswd)");
                $insertStmt->bindParam(':nombre', $name, PDO::PARAM_STR);
                $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
                $insertStmt->bindParam(':pswd', $password, PDO::PARAM_STR);

                $insertStmt->execute();

                $conn->commit();

                echo "
                <script>
                    alert('Registro Exitoso!');
                    window.location.href = 'http://localhost/proyectomedico/login.php';
                </script>
                ";
            } else {
                echo "
                <script>
                    alert('La cuenta ya fue registrada!');
                    window.location.href = 'http://localhost/proyectomedico/login.php';
                </script>
                ";
            }
        } catch (PDOException $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: El campo 'pswd' no puede ser nulo.";
    }
}