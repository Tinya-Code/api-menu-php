# Flujo de Servicios

## Módulo Category

### CategoryController.index()

1. Llamar a `$this->service->getAll()`
2. Convertir cada categoría a array con `toArray()`
3. Retornar JsonResponse con data

### CategoryController.show(int $id)

1. Llamar a `$this->service->getById($id)`
2. Si es null, retornar error 404
3. Convertir categoría a array con `toArray()`
4. Retornar JsonResponse con data

### CategoryController.store(Request $request)

1. Decodificar JSON del request
2. Crear `RegistrarCategoryDTO` con name y description
   - Validación en constructor:
     - name: string, longitud 1-255 caracteres
     - description: string, longitud 0-1000 caracteres
3. Llamar a `$this->service->create($dto)`
4. Retornar JsonResponse con data y status 201
5. Si hay excepción, retornar error 400

### CategoryController.update(int $id, Request $request)

1. Decodificar JSON del request
2. Crear `RegistrarCategoryDTO` con name y description
3. Llamar a `$this->service->update($id, $dto)`
4. Si es null, retornar error 404
5. Convertir categoría a array con `toArray()`
6. Retornar JsonResponse con data
7. Si hay excepción, retornar error 400

### CategoryController.destroy(int $id)

1. Llamar a `$this->service->delete($id)`
2. Si es false, retornar error 404
3. Retornar JsonResponse con mensaje de éxito

### CategoryService.create(RegistrarCategoryDTO $dto)

1. Llamar a `$this->repository->create($dto)`
2. Retornar CategoryEntity

### CategoryRepository.create(RegistrarCategoryDTO $dto)

1. Obtener fecha actual
2. Insertar en tabla categories:
   - name
   - description
   - created_at
   - updated_at
3. Obtener lastInsertId
4. Crear y retornar CategoryEntity

---

## Módulo Product

### ProductController.index()

1. Llamar a `$this->service->getAll()`
2. Convertir cada producto a array con `toArray()`
3. Retornar JsonResponse con data

### ProductController.show(int $id)

1. Llamar a `$this->service->getById($id)`
2. Si es null, retornar error 404
3. Convertir producto a array con `toArray()`
4. Retornar JsonResponse con data

### ProductController.store(Request $request)

1. Decodificar JSON del request
2. Crear `RegistrarProductDTO` con:
   - name (requerido)
   - description (requerido)
   - price (requerido)
   - category_id (opcional)
   - price_range_id (opcional)
   - image_url (opcional)
   - is_active (opcional, default true)
   - Validación en constructor:
     - name: string, longitud 1-255 caracteres
     - description: string, longitud 0-1000 caracteres
     - price: float, mínimo 0
     - category_id: int opcional, mínimo 1
     - price_range_id: int opcional, mínimo 1
     - image_url: string opcional, debe ser URL válida
3. Llamar a `$this->service->create($dto)`
4. Retornar JsonResponse con data y status 201
5. Si hay excepción, retornar error 400

### ProductController.update(int $id, Request $request)

1. Decodificar JSON del request
2. Crear `RegistrarProductDTO` con los mismos campos
3. Llamar a `$this->service->update($id, $dto)`
4. Si es null, retornar error 404
5. Convertir producto a array con `toArray()`
6. Retornar JsonResponse con data
7. Si hay excepción, retornar error 400

### ProductController.destroy(int $id)

1. Llamar a `$this->service->delete($id)`
2. Si es false, retornar error 404
3. Retornar JsonResponse con mensaje de éxito

### ProductService.create(RegistrarProductDTO $dto)

1. Llamar a `$this->repository->create($dto)`
2. Retornar ProductEntity

### ProductRepository.create(RegistrarProductDTO $dto)

1. Obtener fecha actual
2. Insertar en tabla products:
   - name
   - description
   - price
   - category_id
   - price_range_id
   - image_url
   - is_active
   - created_at
   - updated_at
3. Obtener lastInsertId
4. Crear y retornar ProductEntity

---

## Módulo Combo

### ComboController.index()

1. Llamar a `$this->service->getAll()`
2. Convertir cada combo a array con `toArray()`
3. Retornar JsonResponse con data

### ComboController.show(int $id)

1. Llamar a `$this->service->getById($id)`
2. Si es null, retornar error 404
3. Convertir combo a array con `toArray()`
4. Retornar JsonResponse con data

### ComboController.store(Request $request)

1. Decodificar JSON del request
2. Crear `RegistrarComboDTO` con:
   - name (requerido)
   - description (requerido)
   - price (requerido)
   - Validación en constructor:
     - name: string, longitud 1-255 caracteres
     - description: string, longitud 0-1000 caracteres
     - price: float, mínimo 0
3. Llamar a `$this->service->create($dto)`
4. Retornar JsonResponse con data y status 201
5. Si hay excepción, retornar error 400

### ComboController.update(int $id, Request $request)

1. Decodificar JSON del request
2. Crear `RegistrarComboDTO` con los mismos campos
3. Llamar a `$this->service->update($id, $dto)`
4. Si es null, retornar error 404
5. Convertir combo a array con `toArray()`
6. Retornar JsonResponse con data
7. Si hay excepción, retornar error 400

### ComboController.destroy(int $id)

1. Llamar a `$this->service->delete($id)`
2. Si es false, retornar error 404
3. Retornar JsonResponse con mensaje de éxito

### ComboService.create(RegistrarComboDTO $dto)

1. Llamar a `$this->repository->create($dto)`
2. Retornar ComboEntity

### ComboRepository.create(RegistrarComboDTO $dto)

1. Obtener fecha actual
2. Insertar en tabla combos:
   - name
   - description
   - price
   - created_at
   - updated_at
3. Obtener lastInsertId
4. Crear y retornar ComboEntity
