<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Restaurant;
use App\Models\Lead;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard
     */
    public function index(): void
    {
        // Estadísticas
        $restaurantCount = Restaurant::count();
        $leadCount = Lead::count();
        $menuItemCount = MenuItem::count();

        // Últimos registros
        $latestRestaurants = Restaurant::latest(5);
        $latestLeads = Lead::latest(5);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->render('dashboard.index', [
            'restaurantCount' => $restaurantCount,
            'leadCount' => $leadCount,
            'menuItemCount' => $menuItemCount,
            'latestRestaurants' => $latestRestaurants,
            'latestLeads' => $latestLeads,
            'flash' => $flash
        ]);
    }
}
?>
