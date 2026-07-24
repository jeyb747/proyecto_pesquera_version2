<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function flash_set($type, $message) { $_SESSION['flash'] = ['type' => $type, 'message' => $message]; }
function flash_render() {
    if (empty($_SESSION['flash'])) return;
    $flash = $_SESSION['flash']; unset($_SESSION['flash']);
    $type = in_array($flash['type'], ['success','danger','warning','info']) ? $flash['type'] : 'info';
    echo '<div id="app-alert" class="app-alert app-alert-' . $type . '" role="alert">'
       . '<span>' . htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') . '</span>'
       . '<button type="button" aria-label="Cerrar" onclick="this.parentElement.remove()">&times;</button></div>';
}
?>
