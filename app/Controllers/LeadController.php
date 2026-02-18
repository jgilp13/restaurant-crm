<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Core\Csrf;
use App\Models\Lead;
use App\Models\Restaurant;

class LeadController extends Controller
{
    /**
     * Mostrar detalles de lead
     */
    public function edit(): void
    {
        $id = Router::getParam(0);
        $lead = Lead::findById((int)$id);

        if (!$lead) {
            $this->json(['error' => 'Lead no encontrado'], 404);
        }

        $this->render('leads.edit', [
            'lead' => $lead,
            'statuses' => Lead::getValidStatuses(),
            'csrf_token' => Csrf::getToken()
        ]);
    }

    /**
     * Listar leads con filtros
     */
    public function index(): void
    {
        $filters = [
            'status' => $_GET['status'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $leads = Lead::getFiltered($filters);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->render('leads.index', [
            'leads' => $leads,
            'statuses' => Lead::getValidStatuses(),
            'filters' => $filters,
            'csrf_token' => Csrf::getToken(),
            'flash' => $flash
        ]);
    }

    /**
     * Crear lead (AJAX)
     */
    public function store(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'restaurant_name' => $_POST['restaurant_name'] ?? '',
            'status' => $_POST['status'] ?? 'new',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Validar
        $errors = Lead::validate($data);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
        }

        $id = Lead::create($data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Lead creado exitosamente'
        ];

        $this->json(['id' => $id, 'success' => true]);
    }

    /**
     * Actualizar lead (AJAX)
     */
    public function update(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $id = Router::getParam(0);
        $lead = Lead::findById((int)$id);

        if (!$lead) {
            $this->json(['error' => 'Lead no encontrado'], 404);
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'restaurant_name' => $_POST['restaurant_name'] ?? '',
            'status' => $_POST['status'] ?? 'new',
            'notes' => $_POST['notes'] ?? ''
        ];

        // Validar (excluyendo el email actual)
        $errors = Lead::validate($data, (int)$id);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
        }

        Lead::update((int)$id, $data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Lead actualizado exitosamente'
        ];

        $this->json(['success' => true]);
    }

    /**
     * Eliminar lead (AJAX)
     */
    public function delete(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $id = Router::getParam(0);
        $lead = Lead::findById((int)$id);

        if (!$lead) {
            $this->json(['error' => 'Lead no encontrado'], 404);
        }

        Lead::delete((int)$id);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Lead eliminado exitosamente'
        ];

        $this->json(['success' => true]);
    }

    /**
     * Buscar leads (AJAX)
     */
    public function search(): void
    {
        $query = $_POST['q'] ?? '';
        
        if (empty($query)) {
            $this->json(['leads' => []]);
        }

        $leads = Lead::search($query);
        $this->json(['leads' => $leads]);
    }

    /**
     * Buscar restaurantes disponibles (AJAX)
     */
    public function searchRestaurants(): void
    {
        $query = $_POST['q'] ?? '';
        
        if (empty($query)) {
            $this->json(['restaurants' => []]);
            return;
        }

        $restaurants = Restaurant::search($query);
        $this->json(['restaurants' => $restaurants]);
    }
}
?>
