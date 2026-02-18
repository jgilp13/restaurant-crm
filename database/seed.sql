-- Datos de prueba para Restaurant CRM

-- Insertar usuario admin
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Administrador', 'admin@restaurant.crm', 'admin123', 'admin', NOW(), NOW());

-- Insertar restaurantes de prueba
INSERT INTO restaurants (name, email, phone, address, city, description, created_at, updated_at) VALUES
('La Bella Italia', 'info@bellaaitalia.com', '+1 (555) 123-4567', 'Calle Principal 123', 'Los Ángeles', 'Auténtica cocina italiana con ingredientes importados', NOW(), NOW()),
('Sakura Sushi', 'contact@sakura.com', '+1 (555) 234-5678', 'Avenida Central 456', 'Nueva York', 'Exquisito sushi y platos japoneses tradicionales', NOW(), NOW()),
('El Taco Fierro', 'hello@tacos.com', '+1 (555) 345-6789', 'Calle México 789', 'Miami', 'Tacos, tamales y auténtica comida mexicana', NOW(), NOW()),
('Le Petit Bistro', 'reservations@bistro.com', '+1 (555) 456-7890', 'Rue de la Paix 321', 'San Francisco', 'Elegante restaurante francés con ambiente acogedor', NOW(), NOW()),
('Dragons Palace', 'info@dragons.com', '+1 (555) 567-8901', 'Oriental Street 654', 'Chicago', 'Deliciosa comida china en un ambiente refinado', NOW(), NOW());

-- Insertar categorías para La Bella Italia
INSERT INTO categories (restaurant_id, name, description, created_at) VALUES
(1, 'Antipasti', 'Entradas tradicionales italianas', NOW()),
(1, 'Pasta', 'Platos de pasta fresca', NOW()),
(1, 'Risotto', 'Risottos cremosos y variados', NOW()),
(1, 'Postres', 'Dulces italianos auténticos', NOW());

-- Insertar categorías para Sakura Sushi
INSERT INTO categories (restaurant_id, name, description, created_at) VALUES
(2, 'Sushi', 'Rolls y nigiri frescos', NOW()),
(2, 'Sashimi', 'Pescado fresco sin arroz', NOW()),
(2, 'Ramen', 'Sopas tradicionales japonesas', NOW());

-- Insertar categorías para El Taco Fierro
INSERT INTO categories (restaurant_id, name, description, created_at) VALUES
(3, 'Tacos', 'Tacos al pastor y más', NOW()),
(3, 'Enchiladas', 'Enchiladas surtidas', NOW()),
(3, 'Bebidas', 'Bebidas mexicanas tradicionales', NOW());

-- Insertar items de menú para La Bella Italia
INSERT INTO menu_items (restaurant_id, category_id, name, description, price, created_at, updated_at) VALUES
(1, 1, 'Bruschetta al Tomate', 'Pan tostado con tomate, ajo y albahaca', 8.50, NOW(), NOW()),
(1, 1, 'Camarones Importados', 'Camarones a la mantequilla y ajo', 14.99, NOW(), NOW()),
(1, 2, 'Pasta a la Carbonara', 'Pasta con salsa cremosa de huevo y panceta', 16.00, NOW(), NOW()),
(1, 2, 'Fettuccine Alfredo', 'Fettuccine con salsa Alfredo cremosa', 15.50, NOW(), NOW()),
(1, 2, 'Lasaña della Nonna', 'Lasaña tradicional con carne molida', 17.99, NOW(), NOW()),
(1, 3, 'Risotto ai Funghi', 'Risotto con champiñones porcini', 16.50, NOW(), NOW()),
(1, 4, 'Tiramisu', 'Postre italiano tradicional capas de mascarpone', 7.00, NOW(), NOW()),
(1, 4, 'Panna Cotta', 'Crema italiana suave y sedosa', 6.50, NOW(), NOW());

-- Insertar items de menú para Sakura Sushi
INSERT INTO menu_items (restaurant_id, category_id, name, description, price, created_at, updated_at) VALUES
(2, 5, 'California Roll', 'Roll con cangrejo, aguacate y pepino', 9.99, NOW(), NOW()),
(2, 5, 'Dragon Roll', 'Roll especial con anguila y aguacate', 14.99, NOW(), NOW()),
(2, 5, 'Spicy Tuna Roll', 'Roll de atún picante', 11.99, NOW(), NOW()),
(2, 6, 'Sashimi Variado', 'Surtido de pescados frescos', 22.00, NOW(), NOW()),
(2, 7, 'Ramen Tonkotsu', 'Ramen con caldo de hueso de cerdo', 13.00, NOW(), NOW());

-- Insertar items de menú para El Taco Fierro
INSERT INTO menu_items (restaurant_id, category_id, name, description, price, created_at, updated_at) VALUES
(3, 7, 'Tacos al Pastor', 'Tacos con carne marinada y piña', 4.50, NOW(), NOW()),
(3, 7, 'Tacos de Carnitas', 'Tacos con carnitas de cerdo', 4.00, NOW(), NOW()),
(3, 8, 'Enchiladas Verdes', 'Enchiladas con salsa verde', 11.99, NOW(), NOW()),
(3, 9, 'Agua Fresca de Jamaica', 'Bebida refrescante tradicional', 2.50, NOW(), NOW());

-- Insertar leads de prueba
INSERT INTO leads (name, email, phone, restaurant_name, status, notes, created_at, updated_at) VALUES
('Carlos Mendoza', 'carlos@example.com', '+1 (555) 111-1111', 'Restaurante Mi Casa', 'new', 'Contacto por primera vez', NOW(), NOW()),
('María García', 'maria@example.com', '+1 (555) 222-2222', 'Comedor Los Andes', 'contacted', 'Esperando propuesta de menú', NOW(), NOW()),
('Jorge López', 'jorge@example.com', '+1 (555) 333-3333', 'Pizzería Don Juan', 'interested', 'Mostró interés en nuestros servicios', NOW(), NOW()),
('Lucía Fernández', 'lucia@example.com', '+1 (555) 444-4444', 'Parrilla Grill House', 'negotiating', 'En negociaciones, esperando respuesta', NOW(), NOW()),
('Roberto Sánchez', 'roberto@example.com', '+1 (555) 555-5555', 'Marisquería La Costa', 'closed', 'Contrato firmado exitosamente', NOW(), NOW());
