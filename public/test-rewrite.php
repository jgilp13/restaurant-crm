<?php
/**
 * Test para verificar que mod_rewrite está funcionando
 * Accede a: http://restaurant-crm.test/test-rewrite.php
 * o: http://restaurant-crm.test/test/ruta/random
 */

echo '<h1>🔍 Diagnóstico de Mod_Rewrite</h1>';
echo '<hr>';

// Test 1: Verificar si mod_rewrite está activado
echo '<h2>1. ¿Mod_Rewrite está activo?</h2>';
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_rewrite', $modules)) {
        echo '<p style="color: green;"><strong>✓ mod_rewrite SÍ está activo</strong></p>';
    } else {
        echo '<p style="color: red;"><strong>✗ mod_rewrite NO está activo</strong></p>';
    }
} else {
    echo '<p style="color: orange;"><strong>⚠ No se pudo verificar (apache_get_modules no disponible)</strong></p>';
}

// Test 2: Variables de SERVER
echo '<h2>2. Variables del Servidor</h2>';
echo '<table border="1" cellpadding="10" style="margin: 10px 0;">
<tr>
  <th>Variable</th>
  <th>Valor</th>
</tr>
<tr>
  <td>REQUEST_URI</td>
  <td>' . htmlspecialchars($_SERVER['REQUEST_URI']) . '</td>
</tr>
<tr>
  <td>SCRIPT_NAME</td>
  <td>' . htmlspecialchars($_SERVER['SCRIPT_NAME']) . '</td>
</tr>
<tr>
  <td>SCRIPT_FILENAME</td>
  <td>' . htmlspecialchars($_SERVER['SCRIPT_FILENAME']) . '</td>
</tr>
<tr>
  <td>QUERY_STRING</td>
  <td>' . htmlspecialchars($_SERVER['QUERY_STRING'] ?? 'vacío') . '</td>
</tr>
<tr>
  <td>REQUEST_METHOD</td>
  <td>' . htmlspecialchars($_SERVER['REQUEST_METHOD']) . '</td>
</tr>
<tr>
  <td>HTTP_HOST</td>
  <td>' . htmlspecialchars($_SERVER['HTTP_HOST']) . '</td>
</tr>
</table>';

// Test 3: Verificar si la reescritura está funcionando
echo '<h2>3. ¿Está funcionando la reescritura?</h2>';
if (strpos($_SERVER['REQUEST_URI'], 'test-rewrite.php') === false && 
    strpos($_SERVER['SCRIPT_FILENAME'], 'test-rewrite.php') === false) {
    echo '<p style="color: green;"><strong>✓ Reescritura ACTIVA: Se accedió sin test-rewrite.php en la URL</strong></p>';
    echo '<p>Si viniste desde: <code>http://restaurant-crm.test/test/algo/random</code></p>';
    echo '<p>Apache reescribió a index.php internamente ✓</p>';
} else {
    echo '<p style="color: blue;"><strong>ℹ Accediste directamente a test-rewrite.php</strong></p>';
    echo '<p>Prueba accediendo a: <code>http://restaurant-crm.test/test/ruta/random</code></p>';
}

// Test 4: Mostrar .htaccess si existe
echo '<h2>4. Contenido de .htaccess</h2>';
$htaccess = __DIR__ . '/.htaccess';
if (file_exists($htaccess)) {
    echo '<pre style="background: #f4f4f4; padding: 10px; border-left: 3px solid green;">' . 
         htmlspecialchars(file_get_contents($htaccess)) . '</pre>';
    echo '<p style="color: green;">✓ .htaccess existe</p>';
} else {
    echo '<p style="color: red;"><strong>✗ .htaccess NO encontrado</strong></p>';
}

echo '<hr>';
echo '<h3>Notas para diagnóstico:</h3>';
echo '<ul>';
echo '<li>Si REQUEST_URI contiene "test/ruta/random" → mod_rewrite funciona ✓</li>';
echo '<li>Si SCRIPT_FILENAME es "/restaurant-crm/public/index.php" → reescritura correcta ✓</li>';
echo '<li>Si REQUEST_URI empieza con "/" → acceso desde VirtualHost correcto ✓</li>';
echo '</ul>';
?>