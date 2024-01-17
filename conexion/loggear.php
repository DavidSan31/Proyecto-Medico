<?php
require ('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['email'];
    $pswd = $_POST['pswd'];

    $stmt = $conn->prepare("SELECT `pswd`, `email` FROM `datos` WHERE `email` = :email");
    $stmt->bindParam(':email', $name);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();
        $stored_password = $row['pswd'];
        $user_id = $row['email'];

        // Verificar la contraseña utilizando password_verify
        if ($pswd === $stored_password) {
            session_start();
            $_SESSION['user_id'] = $user_id;

            if ($user_id) {
                echo "
                <script>
                    alert('Bienvenido');
                    window.location.href = 'http://localhost/proyectomedico/index.php';
                </script>
                ";
            } 
        } else {
            echo "
            <script>
                alert('Contraseña incorrecta!');
                window.location.href = 'http://localhost/proyectomedico/login.php';
            </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Usuario no encontrado!');
                window.location.href = 'http://localhost/proyectomedico/login.php';
            </script>
            ";
    }
}
