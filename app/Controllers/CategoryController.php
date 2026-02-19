<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Core\Csrf;
use App\Models\Category;
use App\Models\Restaurant;

class CategoryController extends Controller
{
    /**
     * Listar categorías de un restaurante
     */
    public function index(): void
    {
        $restaurantId = Router::getParam(0);
        $restaurant = Restaurant::findById((int)$restaurantId);

        if (!$restaurant) {
            $this->setFlash('error', 'Restaurante no encontrado');
            header('Location: ' . \App\Core\View::url('/restaurants'));
            exit;
        }

        $categories = Category::getByRestaurant((int)$restaurantId);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->render('categories.index', [
            'restaurant' => $restaurant,
            'categories' => $categories,
            'csrf_token' => Csrf::getToken(),
            'flash' => $flash
        ]);
    }

    /**
     * Crear categoría (AJAX)
     */
    public function store(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $restaurantId = (int)($_POST['restaurant_id'] ?? 0);
        $restaurant = Restaurant::findById($restaurantId);

        if (!$restaurant) {
            $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        $data = [
            'restaurant_id' => $restaurantId,
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validar
        $errors = Category::validate($data);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
        }

        $id = Category::create($data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Categoría creada exitosamente'
        ];

        $this->json(['id' => $id, 'success' => true]);
    }

    /**
     * Actualizar categoría (AJAX)
     */
    public function update(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $id = Router::getParam(0);
        $category = Category::findById((int)$id);

        if (!$category) {
            $this->json(['error' => 'Categoría no encontrada'], 404);
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validar (pasando categoryId para exclusión en validación de unicidad)
        $data['restaurant_id'] = $category['restaurant_id'];
        $errors = Category::validate($data, (int)$id);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
        }

        Category::update((int)$id, $data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Categoría actualizada exitosamente'
        ];

        $this->json(['success' => true]);
    }

    /**
     * Eliminar categoría (AJAX)
     */
    public function delete(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $id = Router::getParam(0);
        $category = Category::findById((int)$id);

        if (!$category) {
            $this->json(['error' => 'Categoría no encontrada'], 404);
        }

        Category::delete((int)$id);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Categoría eliminada exitosamente'
        ];

        $this->json(['success' => true]);
    }
}
?>
