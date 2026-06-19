# API Documentation

Base URL: `http://localhost:8000`

## Authentication

Currently, the API does not require authentication for endpoints. JWT authentication is implemented but not enforced on routes.

---

## Categories

### Get All Categories
**GET** `/categories`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Electronics",
      "description": "Electronic devices and accessories",
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Category by ID
**GET** `/categories/{id}`

**Parameters:**
- `id` (path, required) - Category ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Electronics",
    "description": "Electronic devices and accessories",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Category not found"
}
```

### Create Category
**POST** `/categories`

**Request Body:**
```json
{
  "name": "Electronics",
  "description": "Electronic devices and accessories"
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Electronics",
    "description": "Electronic devices and accessories",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Category
**PUT** `/categories/{id}` or **PATCH** `/categories/{id}`

**Parameters:**
- `id` (path, required) - Category ID

**Request Body:**
```json
{
  "name": "Electronics Updated",
  "description": "Updated description"
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Electronics Updated",
    "description": "Updated description",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Category not found"
}
```

### Delete Category
**DELETE** `/categories/{id}`

**Parameters:**
- `id` (path, required) - Category ID

**Response:**
```json
{
  "message": "Category deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Category not found"
}
```

---

## Combos

### Get All Combos
**GET** `/combos`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Premium Combo",
      "description": "Best value package",
      "price": 99.99,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Combo by ID
**GET** `/combos/{id}`

**Parameters:**
- `id` (path, required) - Combo ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Premium Combo",
    "description": "Best value package",
    "price": 99.99,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Combo not found"
}
```

### Create Combo
**POST** `/combos`

**Request Body:**
```json
{
  "name": "Premium Combo",
  "description": "Best value package",
  "price": 99.99
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required
- `price`: float, minimum 0, required

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Premium Combo",
    "description": "Best value package",
    "price": 99.99,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Combo
**PUT** `/combos/{id}` or **PATCH** `/combos/{id}`

**Parameters:**
- `id` (path, required) - Combo ID

**Request Body:**
```json
{
  "name": "Premium Combo Updated",
  "description": "Updated description",
  "price": 109.99
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required
- `price`: float, minimum 0, required

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Premium Combo Updated",
    "description": "Updated description",
    "price": 109.99,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Combo not found"
}
```

### Delete Combo
**DELETE** `/combos/{id}`

**Parameters:**
- `id` (path, required) - Combo ID

**Response:**
```json
{
  "message": "Combo deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Combo not found"
}
```

---

## Gallery

### Get All Gallery Items
**GET** `/gallery`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Product Image",
      "image_url": "https://example.com/image.jpg",
      "description": "Product showcase",
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Gallery Item by ID
**GET** `/gallery/{id}`

**Parameters:**
- `id` (path, required) - Gallery Item ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "title": "Product Image",
    "image_url": "https://example.com/image.jpg",
    "description": "Product showcase",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Gallery item not found"
}
```

### Create Gallery Item
**POST** `/gallery`

**Request Body:**
```json
{
  "title": "Product Image",
  "image_url": "https://example.com/image.jpg",
  "description": "Product showcase"
}
```

**Validation:**
- `title`: string, 1-255 characters, required
- `image_url`: valid URL, required
- `description`: string, 0-1000 characters, optional

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "title": "Product Image",
    "image_url": "https://example.com/image.jpg",
    "description": "Product showcase",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Gallery Item
**PUT** `/gallery/{id}` or **PATCH** `/gallery/{id}`

**Parameters:**
- `id` (path, required) - Gallery Item ID

**Request Body:**
```json
{
  "title": "Product Image Updated",
  "image_url": "https://example.com/new-image.jpg",
  "description": "Updated description"
}
```

**Validation:**
- `title`: string, 1-255 characters, required
- `image_url`: valid URL, required
- `description`: string, 0-1000 characters, optional

**Response:**
```json
{
  "data": {
    "id": 1,
    "title": "Product Image Updated",
    "image_url": "https://example.com/new-image.jpg",
    "description": "Updated description",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Gallery item not found"
}
```

### Delete Gallery Item
**DELETE** `/gallery/{id}`

**Parameters:**
- `id` (path, required) - Gallery Item ID

**Response:**
```json
{
  "message": "Gallery item deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Gallery item not found"
}
```

---

## Price Ranges

### Get All Price Ranges
**GET** `/price-ranges`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Low Range",
      "min_price": 0.00,
      "max_price": 50.00,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Price Range by ID
**GET** `/price-ranges/{id}`

**Parameters:**
- `id` (path, required) - Price Range ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Low Range",
    "min_price": 0.00,
    "max_price": 50.00,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Price range not found"
}
```

### Create Price Range
**POST** `/price-ranges`

**Request Body:**
```json
{
  "name": "Low Range",
  "min_price": 0.00,
  "max_price": 50.00
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `min_price`: float, minimum 0, required
- `max_price`: float, must be >= min_price, required

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Low Range",
    "min_price": 0.00,
    "max_price": 50.00,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Price Range
**PUT** `/price-ranges/{id}` or **PATCH** `/price-ranges/{id}`

**Parameters:**
- `id` (path, required) - Price Range ID

**Request Body:**
```json
{
  "name": "Low Range Updated",
  "min_price": 0.00,
  "max_price": 75.00
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `min_price`: float, minimum 0, required
- `max_price`: float, must be >= min_price, required

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Low Range Updated",
    "min_price": 0.00,
    "max_price": 75.00,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Price range not found"
}
```

### Delete Price Range
**DELETE** `/price-ranges/{id}`

**Parameters:**
- `id` (path, required) - Price Range ID

**Response:**
```json
{
  "message": "Price range deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Price range not found"
}
```

---

## Products

### Get All Products
**GET** `/products`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Laptop",
      "description": "High-performance laptop",
      "price": 999.99,
      "category_id": 1,
      "price_range_id": 2,
      "image_url": "https://example.com/laptop.jpg",
      "is_active": true,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Product by ID
**GET** `/products/{id}`

**Parameters:**
- `id` (path, required) - Product ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Laptop",
    "description": "High-performance laptop",
    "price": 999.99,
    "category_id": 1,
    "price_range_id": 2,
    "image_url": "https://example.com/laptop.jpg",
    "is_active": true,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Product not found"
}
```

### Create Product
**POST** `/products`

**Request Body:**
```json
{
  "name": "Laptop",
  "description": "High-performance laptop",
  "price": 999.99,
  "category_id": 1,
  "price_range_id": 2,
  "image_url": "https://example.com/laptop.jpg",
  "is_active": true
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required
- `price`: float, minimum 0, required
- `category_id`: integer, minimum 1, optional
- `price_range_id`: integer, minimum 1, optional
- `image_url`: valid URL, optional
- `is_active`: boolean, default true, optional

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Laptop",
    "description": "High-performance laptop",
    "price": 999.99,
    "category_id": 1,
    "price_range_id": 2,
    "image_url": "https://example.com/laptop.jpg",
    "is_active": true,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Product
**PUT** `/products/{id}` or **PATCH** `/products/{id}`

**Parameters:**
- `id` (path, required) - Product ID

**Request Body:**
```json
{
  "name": "Laptop Updated",
  "description": "Updated description",
  "price": 1099.99,
  "category_id": 1,
  "price_range_id": 3,
  "image_url": "https://example.com/laptop-new.jpg",
  "is_active": false
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required
- `price`: float, minimum 0, required
- `category_id`: integer, minimum 1, optional
- `price_range_id`: integer, minimum 1, optional
- `image_url`: valid URL, optional
- `is_active`: boolean, default true, optional

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Laptop Updated",
    "description": "Updated description",
    "price": 1099.99,
    "category_id": 1,
    "price_range_id": 3,
    "image_url": "https://example.com/laptop-new.jpg",
    "is_active": false,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Product not found"
}
```

### Delete Product
**DELETE** `/products/{id}`

**Parameters:**
- `id` (path, required) - Product ID

**Response:**
```json
{
  "message": "Product deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Product not found"
}
```

---

## Promotions

### Get All Promotions
**GET** `/promotions`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Summer Sale",
      "description": "Summer discount promotion",
      "discount_percentage": 20.0,
      "start_date": "2024-06-01 00:00:00",
      "end_date": "2024-08-31 23:59:59",
      "is_active": true,
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Promotion by ID
**GET** `/promotions/{id}`

**Parameters:**
- `id` (path, required) - Promotion ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Summer Sale",
    "description": "Summer discount promotion",
    "discount_percentage": 20.0,
    "start_date": "2024-06-01 00:00:00",
    "end_date": "2024-08-31 23:59:59",
    "is_active": true,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Promotion not found"
}
```

### Create Promotion
**POST** `/promotions`

**Request Body:**
```json
{
  "name": "Summer Sale",
  "description": "Summer discount promotion",
  "discount_percentage": 20.0,
  "start_date": "2024-06-01 00:00:00",
  "end_date": "2024-08-31 23:59:59",
  "is_active": true
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required
- `discount_percentage`: float, 0-100, required
- `start_date`: date format Y-m-d H:i:s, optional
- `end_date`: date format Y-m-d H:i:s, optional
- `is_active`: boolean, default true, optional

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "name": "Summer Sale",
    "description": "Summer discount promotion",
    "discount_percentage": 20.0,
    "start_date": "2024-06-01 00:00:00",
    "end_date": "2024-08-31 23:59:59",
    "is_active": true,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Promotion
**PUT** `/promotions/{id}` or **PATCH** `/promotions/{id}`

**Parameters:**
- `id` (path, required) - Promotion ID

**Request Body:**
```json
{
  "name": "Summer Sale Updated",
  "description": "Updated description",
  "discount_percentage": 25.0,
  "start_date": "2024-06-01 00:00:00",
  "end_date": "2024-09-30 23:59:59",
  "is_active": false
}
```

**Validation:**
- `name`: string, 1-255 characters, required
- `description`: string, 0-1000 characters, required
- `discount_percentage`: float, 0-100, required
- `start_date`: date format Y-m-d H:i:s, optional
- `end_date`: date format Y-m-d H:i:s, optional
- `is_active`: boolean, default true, optional

**Response:**
```json
{
  "data": {
    "id": 1,
    "name": "Summer Sale Updated",
    "description": "Updated description",
    "discount_percentage": 25.0,
    "start_date": "2024-06-01 00:00:00",
    "end_date": "2024-09-30 23:59:59",
    "is_active": false,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Promotion not found"
}
```

### Delete Promotion
**DELETE** `/promotions/{id}`

**Parameters:**
- `id` (path, required) - Promotion ID

**Response:**
```json
{
  "message": "Promotion deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Promotion not found"
}
```

---

## Settings

### Get All Settings
**GET** `/settings`

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "key": "site_name",
      "value": "My Admin Panel",
      "description": "Site name displayed in header",
      "created_at": "2024-01-01 00:00:00",
      "updated_at": "2024-01-01 00:00:00"
    }
  ]
}
```

### Get Setting by ID
**GET** `/settings/{id}`

**Parameters:**
- `id` (path, required) - Setting ID

**Response:**
```json
{
  "data": {
    "id": 1,
    "key": "site_name",
    "value": "My Admin Panel",
    "description": "Site name displayed in header",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Setting not found"
}
```

### Create Setting
**POST** `/settings`

**Request Body:**
```json
{
  "key": "site_name",
  "value": "My Admin Panel",
  "description": "Site name displayed in header"
}
```

**Validation:**
- `key`: string, 1-255 characters, required
- `value`: string, 0-5000 characters, required
- `description`: string, 0-1000 characters, optional

**Response (201):**
```json
{
  "data": {
    "id": 1,
    "key": "site_name",
    "value": "My Admin Panel",
    "description": "Site name displayed in header",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
}
```

### Update Setting
**PUT** `/settings/{id}` or **PATCH** `/settings/{id}`

**Parameters:**
- `id` (path, required) - Setting ID

**Request Body:**
```json
{
  "key": "site_name",
  "value": "Updated Admin Panel",
  "description": "Updated description"
}
```

**Validation:**
- `key`: string, 1-255 characters, required
- `value`: string, 0-5000 characters, required
- `description`: string, 0-1000 characters, optional

**Response:**
```json
{
  "data": {
    "id": 1,
    "key": "site_name",
    "value": "Updated Admin Panel",
    "description": "Updated description",
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 12:00:00"
  }
}
```

**Error Response (404):**
```json
{
  "error": "Setting not found"
}
```

### Delete Setting
**DELETE** `/settings/{id}`

**Parameters:**
- `id` (path, required) - Setting ID

**Response:**
```json
{
  "message": "Setting deleted successfully"
}
```

**Error Response (404):**
```json
{
  "error": "Setting not found"
}
```

---

## Error Responses

All endpoints may return the following error responses:

### 400 Bad Request
```json
{
  "error": "Validation error message"
}
```

### 404 Not Found
```json
{
  "error": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
  "error": "Internal server error"
}
```

---

## Setup Instructions

1. Install dependencies:
```bash
composer install
```

2. Create `.env` file in project root:
```env
DB_HOST=localhost
DB_NAME=admin_menu
DB_USER=root
DB_PASSWORD=
DB_DRIVER=pdo_mysql
JWT_SECRET=your-secret-key-here
```

3. Run the server:
```bash
php -S localhost:8000 -t public
```

4. Access the API at `http://localhost:8000`
