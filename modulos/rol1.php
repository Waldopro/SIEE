<?php
if (!isset($_SESSION["id_cargo"]) || $_SESSION["id_cargo"] != 1) {
    header("Location: " . BASE_URL . "/login.php");
    exit();
}
?>