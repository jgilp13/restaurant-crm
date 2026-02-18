<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Core\Csrf;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Listar restaurantes
     */
    public function index(): void
    {
        $restaurants = Restaurant::all();

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->render('restaurants.index', [
            'restaurants' => $restaurants,
            'csrf_token' => Csrf::getToken(),
            'flash' => $flash
        ]);
    }

    /**
     * Mostrar detalles de restaurante
     */
    public function edit(): void
    {
        $id = Router::getParam(0);
        $restaurant = Restaurant::findById((int)$id);

        if (!$restaurant) {
            $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        $this->render('restaurants.edit', [
            'restaurant' => $restaurant,
            'csrf_token' => Csrf::getToken()
        ]);
    }

    /**
     * Crear restaurante (AJAX)
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
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validar
        $errors = Restaurant::validate($data);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
        }

        $id = Restaurant::create($data);
        
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Restaurante creado exitosamente'
        ];

        $this->json(['id' => $id, 'success' => true]);
    }

    /**
     * Actualizar restaurante (AJAX)
     */
    public function update(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $id = Router::getParam(0);
        $restaurant = Restaurant::findById((int)$id);

        if (!$restaurant) {
            $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validar (excluyendo el email actual)
        $errors = Restaurant::validate($data, (int)$id);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
        }

        Restaurant::update((int)$id, $data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Restaurante actualizado exitosamente'
        ];

        $this->json(['success' => true]);
    }

    /**
     * Eliminar restaurante (AJAX)
     */
    public function delete(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $id = Router::getParam(0);
        $restaurant = Restaurant::findById((int)$id);

        if (!$restaurant) {
            $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        Restaurant::delete((int)$id);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Restaurante eliminado exitosamente'
        ];

        $this->json(['success' => true]);
    }

    /**
     * Buscar restaurantes (AJAX)
     */
    public function search(): void
    {
        $query = $_POST['q'] ?? '';
        
        if (empty($query)) {
            $this->json(['restaurants' => []]);
        }

        $restaurants = Restaurant::search($query);
        $this->json(['restaurants' => $restaurants]);
    }
}
?>
