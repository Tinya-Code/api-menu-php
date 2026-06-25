# Arquitectura Técnica

## Módulo Category

### Estructura

- **Controller**: `CategoryController.php`
- **Service**: `CategoryService.php`
- **Repository**: `CategoryRepository.php`
- **DTO**: `RegistrarCategoryDTO.php`
- **Entity**: `CategoryEntity.php`

### Rutas

- `GET /api/categories` - Listar todas las categorías
- `GET /api/categories/{id}` - Obtener una categoría por ID
- `POST /api/categories` - Crear nueva categoría
- `PUT /api/categories/{id}` - Actualizar categoría
- `DELETE /api/categories/{id}` - Eliminar categoría

### Controller (CategoryController.php)

Métodos:

- `index()` - Lista todas las categorías
- `show(int $id)` - Muestra una categoría específica
- `store(Request $request)` - Crea una nueva categoría
- `update(int $id, Request $request)` - Actualiza una categoría
- `destroy(int $id)` - Elimina una categoría

### DTO (RegistrarCategoryDTO.php)

Valida:

- `name` - string, longitud 1-255 caracteres
- `description` - string, longitud 0-1000 caracteres

### Service (CategoryService.php)

Métodos:

- `getAll()` - Obtiene todas las categorías
- `getById(int $id)` - Obtiene una categoría por ID
- `create(RegistrarCategoryDTO $dto)` - Crea una nueva categoría
- `update(int $id, RegistrarCategoryDTO $dto)` - Actualiza una categoría
- `delete(int $id)` - Elimina una categoría

### Repository (CategoryRepository.php)

Métodos:

- `findAll()` - Busca todas las categorías en la base de datos
- `findById(int $id)` - Busca una categoría por ID
- `create(RegistrarCategoryDTO $dto)` - Inserta una nueva categoría
- `update(int $id, RegistrarCategoryDTO $dto)` - Actualiza una categoría
- `delete(int $id)` - Elimina una categoría

---

## Módulo Product

### Estructura

- **Controller**: `ProductController.php`
- **Service**: `ProductService.php`
- **Repository**: `ProductRepository.php`
- **DTO**: `RegistrarProductDTO.php`
- **Entity**: `ProductEntity.php`

### Rutas

- `GET /api/products` - Listar todos los productos
- `GET /api/products/{id}` - Obtener un producto por ID
- `POST /api/products` - Crear nuevo producto
- `PUT /api/products/{id}` - Actualizar producto
- `DELETE /api/products/{id}` - Eliminar producto

### Controller (ProductController.php)

Métodos:

- `index()` - Lista todos los productos
- `show(int $id)` - Muestra un producto específico
- `store(Request $request)` - Crea un nuevo producto
- `update(int $id, Request $request)` - Actualiza un producto
- `destroy(int $id)` - Elimina un producto

### DTO (RegistrarProductDTO.php)

Valida:

- `name` - string, longitud 1-255 caracteres
- `description` - string, longitud 0-1000 caracteres
- `price` - float, mínimo 0
- `category_id` - int opcional, mínimo 1
- `price_range_id` - int opcional, mínimo 1
- `image_url` - string opcional, debe ser URL válida
- `is_active` - bool, default true

### Service (ProductService.php)

Métodos:

- `getAll()` - Obtiene todos los productos
- `getById(int $id)` - Obtiene un producto por ID
- `create(RegistrarProductDTO $dto)` - Crea un nuevo producto
- `update(int $id, RegistrarProductDTO $dto)` - Actualiza un producto
- `delete(int $id)` - Elimina un producto

### Repository (ProductRepository.php)

Métodos:

- `findAll()` - Busca todos los productos en la base de datos
- `findById(int $id)` - Busca un producto por ID
- `create(RegistrarProductDTO $dto)` - Inserta un nuevo producto
- `update(int $id, RegistrarProductDTO $dto)` - Actualiza un producto
- `delete(int $id)` - Elimina un producto

---

## Módulo Combo

### Estructura

- **Controller**: `ComboController.php`
- **Service**: `ComboService.php`
- **Repository**: `ComboRepository.php`
- **DTO**: `RegistrarComboDTO.php`
- **Entity**: `ComboEntity.php`

### Rutas

- `GET /api/combos` - Listar todos los combos
- `GET /api/combos/{id}` - Obtener un combo por ID
- `POST /api/combos` - Crear nuevo combo
- `PUT /api/combos/{id}` - Actualizar combo
- `DELETE /api/combos/{id}` - Eliminar combo

### Controller (ComboController.php)

Métodos:

- `index()` - Lista todos los combos
- `show(int $id)` - Muestra un combo específico
- `store(Request $request)` - Crea un nuevo combo
- `update(int $id, Request $request)` - Actualiza un combo
- `destroy(int $id)` - Elimina un combo

### DTO (RegistrarComboDTO.php)

Valida:

- `name` - string, longitud 1-255 caracteres
- `description` - string, longitud 0-1000 caracteres
- `price` - float, mínimo 0

### Service (ComboService.php)

Métodos:

- `getAll()` - Obtiene todos los combos
- `getById(int $id)` - Obtiene un combo por ID
- `create(RegistrarComboDTO $dto)` - Crea un nuevo combo
- `update(int $id, RegistrarComboDTO $dto)` - Actualiza un combo
- `delete(int $id)` - Elimina un combo

### Repository (ComboRepository.php)

Métodos:

- `findAll()` - Busca todos los combos en la base de datos
- `findById(int $id)` - Busca un combo por ID
- `create(RegistrarComboDTO $dto)` - Inserta un nuevo combo
- `update(int $id, RegistrarComboDTO $dto)` - Actualiza un combo
- `delete(int $id)` - Elimina un combo

---

## Tecnologías Utilizadas

- **PHP 8+** con tipado estricto (`declare(strict_types=1)`)
- **Symfony HttpFoundation** para manejo de Request y JsonResponse
- **Doctrine DBAL** para conexión a base de datos
- **Respect\Validation** para validación de DTOs
- **Patrón MVC** con separación de responsabilidades
- **Patrón Repository** para acceso a datos
- **DTO (Data Transfer Objects)** para validación y transferencia de datos
