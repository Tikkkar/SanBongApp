# Báo Cáo Đồ Án Quản Lý Sân Bóng

## Chương 1. Cơ sở lý thuyết
*(Những công nghệ chính sử dụng trong đồ án)*

- **Ngôn ngữ lập trình:** PHP 8+
- **Framework:** Laravel 11.x
- **Cơ sở dữ liệu:** SQLite (hoặc MySQL tùy môi trường triển khai)
- **Frontend:** HTML, CSS, Bootstrap 5, Javascript thuần
- **Kiến trúc hệ thống:** MVC (Model - View - Controller)

## Chương 2. Khảo sát và phân tích hệ thống

### 2.1. Khảo sát hiện trạng và Nhu cầu thực tế
Qua khảo sát thực tế tại các cụm sân bóng đá mini, việc quản lý hiện tại gặp nhiều bất cập:
- **Quản lý thủ công:** Sử dụng sổ tay hoặc nhóm chat để nhận lịch, dẫn đến việc khó tra cứu lại lịch cũ hoặc dễ bị bỏ sót yêu cầu của khách.
- **Xung đột khung giờ:** Không có hệ thống cảnh báo khi hai khách hàng cùng đặt một sân vào một thời điểm trên các kênh khác nhau (gọi điện, nhắn tin).
- **Khó khăn trong tổ chức giải đấu:** Các giải đấu phong trào thường có quy trình chia bảng, xếp lịch thi đấu và tính điểm rất phức tạp. Việc tính toán thủ công trên giấy tờ hoặc Excel thường xuyên xảy ra nhầm lẫn về hiệu số, số bàn thắng, ảnh hưởng đến tính công bằng của giải đấu.

### 2.2. Phân tích các tác nhân (Actors)
- **Quản trị viên (Admin):** 
  - Quản lý hạ tầng (Sân bóng, loại sân, hình ảnh).
  - Thiết lập giá bán linh hoạt theo đặc thù khung giờ (Giờ vàng, giờ hành chính).
  - Điều phối và phê duyệt đơn đặt sân của khách hàng.
  - Tổ chức giải đấu: Thiết lập thông số giải, duyệt đội tham gia, chia bảng đấu, xếp lịch và cập nhật kết quả.
- **Khách hàng (Customer):**
  - Tra cứu thông tin sân, xem lịch trống trực tuyến giúp chủ động thời gian.
  - Thực hiện đặt sân và theo dõi trạng thái đơn hàng.
  - Xem thông tin giải đấu, lịch thi đấu và bảng xếp hạng để cập nhật tình hình đội bóng yêu thích.

### 2.3. Yêu cầu chức năng (Functional Requirements)
- **Modul Quản lý Sân:** Cho phép quản lý chi tiết từng sân, bật/tắt trạng thái hoạt động.
- **Modul Giá cả:** Hỗ trợ giá động (Dynamic Pricing) theo khung giờ cụ thể.
- **Modul Đặt sân:** Xử lý logic kiểm tra trùng lịch (Overlap check) và quản lý hóa đơn.
- **Modul Giải đấu chuyên nghiệp:** 
  - Hỗ trợ đa dạng thể thức: Vòng tròn tính điểm, Loại trực tiếp, hoặc Chia bảng đấu.
  - **Quản lý Bảng đấu:** Tự động hóa việc phân chia và hiển thị BXH riêng biệt cho từng bảng (Bảng A, B, C...).
  - **Cập nhật kết quả:** Hệ thống tự động tính điểm, hiệu số và bàn thắng ngay sau khi nhập tỉ số trận đấu.

## Chương 3. Thiết kế hệ thống

### 3.1. Thiết kế Cơ sở dữ liệu (Database Schema)
Hệ thống sử dụng mô hình quan hệ để đảm bảo tính toàn vẹn dữ liệu và tối ưu hóa truy vấn:
- `users`: Lưu trữ thông tin định danh, mật khẩu băm (hash) và phân quyền (Admin/Customer).
- `pitches`: Thông tin chi tiết về cụm sân, loại sân (5, 7, 11 người) và trạng thái hiển thị.
- `pitch_prices`: Cấu hình bảng giá linh hoạt, cho phép thiết lập giá khác nhau giữa các khung giờ vàng và giờ thường.
- `bookings`: Lưu trữ lịch sử đặt sân, liên kết 1-n giữa người dùng và sân bóng, quản lý thời gian bắt đầu/kết thúc.
- `tournaments`: Bảng quản lý giải đấu, lưu trữ Logo, Thể thức thi đấu (League/Knockout/GroupStage), Phí tham gia, Giải thưởng và các ràng buộc về số đội.
- `teams`: Quản lý danh sách đội bóng, lưu trữ định danh "Bảng đấu" (Group name) giúp hệ thống tự động phân loại BXH theo bảng A, B, C...
- `matches`: Lưu vết lịch thi đấu, kết quả tỉ số và gán tên vòng đấu cụ thể (Vòng bảng, Tứ kết, Chung kết...).

### 3.2. Kiến trúc và Giao diện (UI/UX Design)
- **Mô hình MVC:** Hệ thống được phát triển trên kiến trúc Model-View-Controller của Laravel, giúp tách biệt logic nghiệp vụ, giao diện và tương tác dữ liệu, dễ dàng cho việc bảo trì lâu dài.
- **Thiết kế Responsive:** Sử dụng công nghệ Bootstrap 5 kết hợp với CSS Grid/Flexbox để đảm bảo giao diện hiển thị tối ưu trên mọi kích thước màn hình, từ Dashboard quản trị trên Desktop đến trang đặt sân gọn nhẹ trên Mobile.
- **Trải nghiệm người dùng:** Giao diện Dashboard được thiết kế trực quan với các chỉ số thống kê, danh sách đặt sân mới và biểu đồ trực quan giúp Admin nắm bắt tình hình kinh doanh nhanh chóng.

## Chương 4. Triển khai
*(Cách cài đặt, bảo vệ, hoạt động thử nghiệm, tài liệu hướng dẫn)*

### 4.1. Hướng dẫn cài đặt
1. Cài đặt các phần mềm yêu cầu: PHP (version >= 8.2), Composer.
2. Clone bản sao dự án.
3. Chạy lệnh cài đặt thư viện: `composer install`
4. Cấu hình file môi trường `.env`.
5. Tạo cơ sở dữ liệu: `php artisan migrate --seed`
6. Khởi chạy máy chủ ảo (dev server): `php artisan serve`

### 4.2. Bảo vệ và bảo mật
- Chứng thực người dùng bằng hệ thống Session Guard và CSRF Token tích hợp sẵn của Laravel.
- Middleware kiểm tra quyền truy cập Quản trị viên (`EnsureUserIsAdmin`).

### 4.3. Quá trình hoạt động thử nghiệm
- **Kiểm thử tìm kiếm và đặt sân:** Dữ liệu mẫu đảm bảo hệ thống không cho phép 2 khách đặt trùng một khung giờ.
- **Kiểm thử luồng Giải bóng đá:**
  - Quy trình đăng ký và phê duyệt đội bóng.
  - **Tính năng chia bảng (Phân phối bảng đấu):** Admin gán đội vào Bảng A, B... và hệ thống tự động tính toán BXH riêng biệt cho từng bảng.
  - **Lập lịch thi đấu thủ công:** Hỗ trợ chọn vòng đấu cụ thể, quản lý danh sách trận đấu theo thời gian.
  - **Cập nhật tỉ số:** Tự động nhảy điểm số, hiệu số trên BXH ngay khi trận đấu kết thúc.
- **Các Use Case (UC) chính:** Quản lý sân bóng; quản lý người dùng; quản lý lịch đặt sân; thực hiện đặt sân và thanh toán; quản lý giải bóng đá (chia bảng, xếp lịch, BXH); quản lý đội bóng; quản lý kết quả thi đấu. 

- **Các Use Case (UC) phụ:** Đăng ký tài khoản; đăng nhập hệ thống; tìm kiếm sân bóng; lọc sân bóng theo tiêu chí; xem thông tin chi tiết sân bóng; xem lịch trống của sân; thực hiện đặt sân; đăng ký đội bóng tham gia giải; xem lịch thi đấu; xem bảng xếp hạng giải đấu. 

### 4.4. Tài liệu hướng dẫn sử dụng
- **Đối với Admin:** Đăng nhập > Dashboard > Quản lý Giải đấu > Tạo giải đấu với đủ thông tin > Duyệt đội > Chia bảng > Xếp lịch > Cập nhật Tỉ số.
- **Đối với Khách hàng:** Đăng nhập > Chọn sân > Chọn giờ trống > Xác nhận đặt sân.

## Chương 5. Kết luận và Hướng phát triển

### 5.1. Kết quả đạt được
- Xây dựng thành công hệ thống quản lý sân bóng toàn diện với giao diện hiện đại, dễ sử dụng.
- Giải quyết triệt để bài toán tổ chức giải đấu bóng đá phong trào với tính năng chia bảng và tự động hóa bảng xếp hạng.
- Đảm bảo tính bảo mật và hiệu năng cao nhờ nền tảng Laravel Framework.

### 5.2. Hướng phát triển tương lai
- Tích hợp cổng thanh toán trực tuyến (Momo, VNPay) để tự động hóa quy trình đặt cọc.
- Phát triển module thông báo (Notifications) qua Email hoặc Telegram cho khách hàng và quản lý đội bóng.
- Hệ thống thống kê chuyên sâu về phong độ cầu thủ và ghi bàn trong giải đấu.
