<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Test controller for basic functionality checks
 */
class TestController extends Controller
{
    /**
     * Test session
     */
    public function session(): void
    {
        session_regenerate_id(true);
        $_SESSION['test_value'] = 'Session is working!';
        $_SESSION['test_time'] = time();

        echo '<h1>✅ Session Test</h1>';
        echo '<p>Session ID: ' . session_id() . '</p>';
        echo '<p>Status: ' . session_status() . '</p>';
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
        
        echo '<h2>Next Steps:</h2>';
        echo '<ol>';
        echo '<li><a href="/test/session-check">Check if data persists</a></li>';
        echo '<li><a href="/debug/csrf">Test CSRF token</a></li>';
        echo '<li><a href="/">Go to login</a></li>';
        echo '</ol>';
    }

    /**
     * Check if session persists
     */
    public function sessionCheck(): void
    {
        echo '<h1>Session Check</h1>';
        if (isset($_SESSION['test_value'])) {
            echo '<p style="color: green;"><strong>✅ Session persists!</strong></p>';
            echo '<p>Stored value: ' . $_SESSION['test_value'] . '</p>';
            echo '<p>Time stored: ' . $_SESSION['test_time'] . '</p>';
            echo '<p>Time now: ' . time() . '</p>';
        } else {
            echo '<p style="color: red;"><strong>❌ Session lost!</strong></p>';
            echo '<p>This means session data is not persisting between requests.</p>';
        }
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
        echo '<p><a href="/test/session">Start new test</a></p>';
    }
}
?>