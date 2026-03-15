# Hướng Dẫn Khởi Chạy Dự Án SanBongApp

Do môi trường của bạn chưa nhận diện lệnh `php` trực tiếp, bạn cần sử dụng đường dẫn tuyệt đối hoặc cấu hình lại biến môi trường.

## 1. Đường dẫn công cụ trên máy của bạn
- **PHP**: `C:\php\php.exe`
- **Composer**: `C:\php\composer.bat` (Hoặc chạy qua PHP: `C:\php\php.exe C:\php\composer.phar`)

---

## 2. Các bước khởi chạy dự án

### Bước 1: Di chuyển vào thư mục dự án
Mở terminal (PowerShell hoặc CMD) và gõ:
```powershell
cd d:\Dev\DoAnSanBong\SanBongApp
```

### Bước 2: Cài đặt thư viện (nếu chưa có)
Chạy lệnh sau để cập nhật các thư viện cần thiết:
```powershell
C:\php\php.exe C:\php\composer.phar install
```

### Bước 3: Cấu hình Cơ sở dữ liệu
Nếu bạn đã chạy migrate rồi thì có thể bỏ qua. Nếu chưa, hãy chạy:
```powershell
C:\php\php.exe artisan migrate --seed
```

### Bước 4: Khởi chạy Server
Để mở trang web trên trình duyệt (`http://127.0.0.1:8000`), hãy chạy:
```powershell
C:\php\php.exe artisan serve
```

---

## 3. Cách sửa lỗi "php is not recognized" (Vĩnh viễn)

Để có thể gõ `php artisan serve` thay vì phải gõ cả đường dẫn dài, bạn làm như sau:

1. Nhấn phím **Windows**, gõ "env" và chọn **"Edit the system environment variables"**.
2. Nhấn nút **Environment Variables**.
3. Ở ô **User variables**, tìm dòng **Path**, chọn nó và nhấn **Edit**.
4. Nhấn **New**, và dán đường dẫn: `C:\php`
5. Nhấn **OK** ở tất cả các cửa sổ.
6. **Quan trọng:** Tắt terminal cũ đi và mở một cái mới để áp dụng thay đổi.

Bây giờ bạn có thể chạy các lệnh bình thường:
```powershell
php artisan serve
```

---

## 4. Tài khoản đăng nhập mẫu (nếu có Seed)
- **Admin**: `admin@gmail.com` / mât khẩu: `password` (Kiểm tra trong `DatabaseSeeder.php` để biết chính xác).
