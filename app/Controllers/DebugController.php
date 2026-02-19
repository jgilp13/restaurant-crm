<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;

/**
 * Controlador de debug para diagnosticar problemas
 */
class DebugController extends Controller
{
    /**
     * Mostrar información de sesión y CSRF
     */
    public function csrf(): void
    {
        echo '<h1>🔍 DEBUG - CSRF Token Validator</h1>';
        
        echo '<div style="background: #f0f0f0; padding: 15px; margin: 15px 0; border-radius: 5px;">';
        echo '<h2>Session Info</h2>';
        echo '<pre>';
        echo "Session ID: " . htmlspecialchars(session_id()) . "\n";
        echo "Session Status: " . session_status() . " (1=disabled, 2=none, 3=active)\n";
        echo "Session Active: " . (session_status() === PHP_SESSION_ACTIVE ? "✅ YES" : "❌ NO") . "\n";
        echo '</pre>';
        echo '</div>';

        echo '<div style="background: #f0f0f0; padding: 15px; margin: 15px 0; border-radius: 5px;">';
        echo '<h2>CSRF Token</h2>';
        echo '<pre>';
        $token = Csrf::getToken();
        echo "Current Token: " . htmlspecialchars(substr($token, 0, 32)) . "...\n";
        echo "Token Length: " . strlen($token) . " chars\n";
        echo "Token Set: " . (isset($_SESSION['_csrf_token']) ? "✅ YES" : "❌ NO") . "\n";
        echo '</pre>';
        echo '</div>';

        echo '<div style="background: #f0f0f0; padding: 15px; margin: 15px 0; border-radius: 5px;">';
        echo '<h2>Full Session Data</h2>';
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
        echo '</div>';

        if (!empty($_POST)) {
            echo '<div style="background: #fff3cd; padding: 15px; margin: 15px 0; border-radius: 5px; border: 1px solid #ffc107;">';
            echo '<h2>🧪 Test Result</h2>';
            $postToken = $_POST['_csrf_token'] ?? 'NOT FOUND';
            $isValid = Csrf::verifyPost();
            
            echo '<table style="width: 100%; border-collapse: collapse;">';
            echo '<tr style="background: #f0f0f0;"><td style="border: 1px solid #ddd; padding: 8px;"><strong>Received Token:</strong></td><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars(substr($postToken, 0, 32)) . '...</td></tr>';
            echo '<tr><td style="border: 1px solid #ddd; padding: 8px;"><strong>Expected Token:</strong></td><td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars(substr($token, 0, 32)) . '...</td></tr>';
            echo '<tr style="background: #e8f5e9;"><td style="border: 1px solid #ddd; padding: 8px;"><strong>Validation:</strong></td><td style="border: 1px solid #ddd; padding: 8px;"><strong style="font-size: 18px; color: ' . ($isValid ? 'green' : 'red') . ';">' . ($isValid ? '✅ VALID' : '❌ INVALID') . '</strong></td></tr>';
            echo '</table>';
            echo '</div>';
        }

        echo '<div style="background: #e3f2fd; padding: 15px; margin: 15px 0; border-radius: 5px;">';
        echo '<h2>📝 Test Form</h2>';
        echo '<form method="POST" action="/debug/csrf" style="margin-top: 10px;">';
        echo Csrf::input();
        echo '<input type="hidden" name="test" value="1">';
        echo '<button type="submit" style="padding: 10px 20px; background: #2196F3; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 16px;">Submit Test Form</button>';
        echo '</form>';
        echo '</div>';

        echo '<div style="background: #fff3e0; padding: 15px; margin: 15px 0; border-radius: 5px;">';
        echo '<h2>💡 Tips</h2>';
        echo '<ul>';
        echo '<li><strong>Green check (✅ VALID)</strong> = CSRF token is working correctly</li>';
        echo '<li><strong>Red X (❌ INVALID)</strong> = Token mismatch or session issue</li>';
        echo '<li>Clear your browser cookies if you see repeated failures</li>';
        echo '<li>Check PHP error logs for detailed debug messages</li>';
        echo '</ul>';
        echo '</div>';
    }
}
?>
