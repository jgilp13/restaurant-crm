<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Core\Csrf;
use App\Models\Restaurant;
use App\Models\MenuItem;
use App\Models\Category;

class MenuController extends Controller
{
    private int $perPage = 15;

    /**
     * Listar menú de un restaurante
     */
    public function index(): void
    {
        $restaurantId = Router::getParam(0);
        $restaurant = Restaurant::findById($restaurantId);

        if (!$restaurant) {
            $this->setFlash('error', 'Restaurante no encontrado');
            $this->redirect('/restaurants');
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $category = $_GET['category'] ?? '';
        $page = $page < 1 ? 1 : $page;

        if ($category) {
            $items = MenuItem::filterByCategory($restaurantId, $category, $page, $this->perPage);
            $total = MenuItem::countByCategory($restaurantId, $category);
        } else {
            $items = MenuItem::filterByRestaurant($restaurantId, $page, $this->perPage);
            $total = MenuItem::countByRestaurant($restaurantId);
        }

        $categories = Category::getByRestaurant($restaurantId);
        $totalPages = ceil($total / $this->perPage);

        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        $this->render('menu.index', [
            'restaurant' => $restaurant,
            'items' => $items,
            'categories' => $categories,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'perPage' => $this->perPage,
            'currentCategory' => $category,
            'flash' => $flash
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): void
    {
        $restaurantId = Router::getParam(0);
        $restaurant = Restaurant::findById($restaurantId);

        if (!$restaurant) {
            $this->setFlash('error', 'Restaurante no encontrado');
            $this->redirect('/restaurants');
        }

        $categories = Category::getByRestaurant($restaurantId);

        $this->render('menu.create', [
            'restaurant' => $restaurant,
            'categories' => $categories,
            'csrf_token' => Csrf::getToken()
        ]);
    }

    /**
     * Guardar nuevo item de menú
     */
    public function store(): void
    {
        if (!Csrf::verifyPost()) {
            $this->setFlash('error', 'Token CSRF inválido');
            $this->redirect('/menu/' . Router::getParam(0));
        }

        $restaurantId = Router::getParam(0);
        $restaurant = Restaurant::findById($restaurantId);

        if (!$restaurant) {
            $this->setFlash('error', 'Restaurante no encontrado');
            $this->redirect('/restaurants');
        }

        $data = [
            'restaurant_id' => $restaurantId,
            'category_id' => $_POST['category_id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? ''
        ];

        $errors = $this->validate($data, [
            'category_id' => 'required|numeric',
            'name' => 'required|min:3|max:100',
            'price' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $this->redirect('/menu/' . $restaurantId . '/create');
        }

        MenuItem::create($data);
        $this->setFlash('success', 'Platillo creado exitosamente');
        $this->redirect('/menu/' . $restaurantId);
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(): void
    {
        $restaurantId = Router::getParam(0);
        $itemId = Router::getParam(1);

        $restaurant = Restaurant::findById($restaurantId);
        $item = MenuItem::findById($itemId);

        if (!$restaurant || !$item || $item['restaurant_id'] != $restaurantId) {
            $this->setFlash('error', 'Platillo no encontrado');
            $this->redirect('/restaurants');
        }

        $categories = Category::getByRestaurant($restaurantId);

        $this->render('menu.edit', [
            'restaurant' => $restaurant,
            'item' => $item,
            'categories' => $categories,
            'csrf_token' => Csrf::getToken()
        ]);
    }

    /**
     * Actualizar item de menú
     */
    public function update(): void
    {
        if (!Csrf::verifyPost()) {
            $this->setFlash('error', 'Token CSRF inválido');
            $this->redirect('/menu/' . Router::getParam(0));
        }

        $restaurantId = Router::getParam(0);
        $itemId = Router::getParam(1);

        $restaurant = Restaurant::findById($restaurantId);
        $item = MenuItem::findById($itemId);

        if (!$restaurant || !$item || $item['restaurant_id'] != $restaurantId) {
            $this->setFlash('error', 'Platillo no encontrado');
            $this->redirect('/restaurants');
        }

        $data = [
            'category_id' => $_POST['category_id'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price' => $_POST['price'] ?? ''
        ];

        $errors = $this->validate($data, [
            'category_id' => 'required|numeric',
            'name' => 'required|min:3|max:100',
            'price' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $this->redirect('/menu/' . $restaurantId . '/edit/' . $itemId);
        }

        MenuItem::update($itemId, $data);
        $this->setFlash('success', 'Platillo actualizado exitosamente');
        $this->redirect('/menu/' . $restaurantId);
    }

    /**
     * Eliminar item de menú
     */
    public function delete(): void
    {
        if (!Csrf::verifyPost()) {
            $this->json(['error' => 'Token CSRF inválido'], 403);
        }

        $restaurantId = Router::getParam(0);
        $itemId = Router::getParam(1);

        $item = MenuItem::findById($itemId);

        if (!$item || $item['restaurant_id'] != $restaurantId) {
            $this->json(['error' => 'Platillo no encontrado'], 404);
        }

        MenuItem::delete($itemId);
        $this->setFlash('success', 'Platillo eliminado exitosamente');
        $this->redirect('/menu/' . $restaurantId);
    }
}
?>
