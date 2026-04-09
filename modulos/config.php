<?php
// ============================================
// Configuración central del sistema UEBEM
// ============================================

define('BASE_URL', '/sistema-UEBEM');

// Cargar variables de entorno desde .env
$env_path = $_SERVER['DOCUMENT_ROOT'] . '/sistema-UEBEM/.env';
if (file_exists($env_path)) {
    $lines = file($env_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!defined($key)) {
                define($key, $value);
            }
        }
    }
}

// Configuración de sesión segura (llamar ANTES de session_start)
function configurar_sesion_segura() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 0); // Cambiar a 1 cuando se tenga HTTPS
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Lax');
        session_start();
    }
}

// ============================================
// Funciones CSRF
// ============================================

/**
 * Genera un token CSRF y lo almacena en la sesión
 */
function generar_token_csrf() {
    if (session_status() === PHP_SESSION_NONE) {
        configurar_sesion_segura();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Devuelve un campo input hidden con el token CSRF para usar en formularios
 */
function campo_csrf() {
    $token = generar_token_csrf();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * Verifica que el token CSRF enviado sea válido
 */
function verificar_csrf() {
    if (session_status() === PHP_SESSION_NONE) {
        configurar_sesion_segura();
    }
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/**
 * Verifica CSRF y muere con error si no es válido
 */
function exigir_csrf() {
    if (!verificar_csrf()) {
        http_response_code(403);
        die('Error: Token de seguridad inválido. Por favor, recargue la página e intente de nuevo.');
    }
}

// ============================================
// Función de escape para salida HTML
// ============================================

/**
 * Escapa una cadena para salida HTML segura (previene XSS)
 */
function esc($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

?>
