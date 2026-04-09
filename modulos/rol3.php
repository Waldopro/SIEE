<?php
if (!isset($_SESSION["id_cargo"]) || $_SESSION["id_cargo"] != 3) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}
?>