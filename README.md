# Laravel Filament Admin - MSSV: 23810310109

## Mô tả
Phân hệ Admin quản lý danh mục và sản phẩm sử dụng Laravel + Filament v3.

## Cấu trúc Database
- `23810310109_categories`: Bảng danh mục
- `23810310109_products`: Bảng sản phẩm

## Tính năng
- Quản lý Danh mục: auto-slug, filter is_visible
- Quản lý Sản phẩm: Grid layout, RichEditor, upload ảnh, giá VNĐ, tìm kiếm, lọc theo danh mục
- Trường sáng tạo: `discount_percent` (phần trăm giảm giá 0-100%)
- Primary color: Teal

## Cài đặt
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan make:filament-user
php artisan serve
```
