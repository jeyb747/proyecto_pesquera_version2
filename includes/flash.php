<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function flash_set($type, $message) { $_SESSION['flash'] = ['type' => $type, 'message' => $message]; }
function flash_render() {
    if (empty($_SESSION['flash'])) return;
    $flash = $_SESSION['flash']; unset($_SESSION['flash']);
    $type = in_array($flash['type'], ['success','danger','warning','info']) ? $flash['type'] : 'info';
    echo '<link rel="stylesheet" href="/css/alerts.css">'
       . '<div class="app-alert-backdrop" role="presentation">'
       . '<div id="app-alert" class="app-alert app-alert-' . $type . '" role="alert">'
       . '<div class="app-alert-icon" aria-hidden="true">i</div>'
       . '<h2>Aviso</h2>'
       . '<p>' . htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') . '</p>'
       . '<button type="button" onclick="this.closest(\'.app-alert-backdrop\').remove()">Entendido</button>'
       . '</div></div>';
}
?>
