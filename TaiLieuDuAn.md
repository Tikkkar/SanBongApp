# TÀI LIỆU ĐẶC TẢ YÊU CẦU DỰ ÁN

## 1. Tên đề tài
**Xây dựng hệ thống website đặt sân bóng đá và hỗ trợ tổ chức giải đấu bóng đá.**

---

## 2. Mục tiêu dự án
Xây dựng một hệ thống website trực tuyến với giao diện hiện đại, thân thiện, dễ sử dụng nhằm phục vụ hai mục đích chính:
1. **Dành cho người dùng (Khách hàng):** Tìm kiếm thông tin sân bóng, xem lịch trống và đặt sân theo ngày/khung giờ. Tham gia và theo dõi các giải đấu bóng đá.
2. **Dành cho Chủ sân/Quản trị viên:** Quản lý sân bóng, lịch đặt, doanh thu và tổ chức/quản lý các giải đấu bóng đá một cách chuyên nghiệp.

---

## 3. Quy trình nghiệp vụ (BPMN)

- **Quy trình đặt sân:** 
  Xem thông tin sân bóng $\rightarrow$ Chọn sân và khung giờ $\rightarrow$ Thực hiện đặt sân $\rightarrow$ Xác nhận đặt sân $\rightarrow$ Thanh toán. 
- **Quy trình quản lý chung:** 
  Quản lý thông tin sân bóng, quản lý người dùng, quản lý lịch đặt sân, thống kê và báo cáo doanh thu. 
- **Quy trình tổ chức giải đấu:** 
  Tạo giải đấu $\rightarrow$ Đăng ký đội bóng $\rightarrow$ Lập lịch thi đấu $\rightarrow$ Cập nhật kết quả $\rightarrow$ Hiển thị bảng xếp hạng. 
- **Quy trình hỗ trợ:**
  Xử lý lịch đặt sân; Chăm sóc người dùng (hỗ trợ qua Dialogflow).

---

## 4. Các Tác nhân (Actors) và Quyền hạn

### 4.1. Khách hàng (User)
- Xem thông tin, tìm kiếm và lọc sân bóng phù hợp.
- Xem lịch trống, chọn khung giờ và đặt sân trực tuyến, thanh toán.
- Đăng ký đội bóng tham gia vào các giải đấu do hệ thống hoặc chủ sân tổ chức.
- Xem lịch thi đấu, kết quả các trận đấu và bảng xếp hạng giải đấu.

### 4.2. Nhân viên / Quản trị viên (Admin)
- Quản lý thông tin danh mục và chi tiết các sân bóng.
- Quản lý lịch đặt sân, duyệt/hủy đặt sân.
- Quản lý người dùng và phân quyền.
- Tổ chức quản lý giải đấu: phê duyệt đội bóng tham gia, xếp lịch thi đấu, cập nhật tỷ số trận đấu.
- Thống kê, báo cáo doanh thu hoạt động và tình trạng sử dụng sân.

---

## 5. Danh sách Yêu cầu Hệ thống

### 5.1. Yêu cầu Chức năng (Functional Requirements)
- Quản lý danh mục sân bóng và thông tin chi tiết từng sân.
- Quản lý đặt sân, lịch đặt sân, theo dõi cập nhật trạng thái đặt sân.
- Tìm kiếm và lọc sân bóng theo nhiều tiêu chí.
- Đăng ký, đăng nhập và phân quyền hệ thống.
- Trang quản trị thống kê hệ thống toàn diện (Admin Dashboard).
- Quản lý tổ chức giải đấu: Giải bóng, các đội tham gia, lịch thi đấu, cập nhật kết quả, tự động cập nhật bảng xếp hạng.

### 5.2. Yêu cầu Phi chức năng (Non-functional Requirements)
- **Hiệu năng:** Thời gian phản hồi hệ thống nhỏ hơn 3 giây.
- **Bảo mật:** Mã hóa thông tin mật khẩu bảo mật, phân quyền truy cập chặt chẽ.
- **Dữ liệu:** Đảm bảo tính toàn vẹn và độ chính xác của cơ sở dữ liệu. Thiết kế sơ đồ lớp và ERD tuân thủ các chuẩn: khóa chính, khóa ngoại, ràng buộc rõ ràng.
- **Mở rộng:** Kiến trúc hệ thống sẵn sàng mở rộng module (scaling) trong tương lai.
- **Giao diện:** UI/UX thiết kế thân thiện, responsive đa nền tảng, dễ thao tác sử dụng.

---

## 6. Phân tích Use Case (UC)

### 6.1. Các Use Case Chính
1. Quản lý sân bóng
2. Quản lý người dùng
3. Quản lý lịch đặt sân
4. Thực hiện đặt sân và thanh toán
5. Quản lý giải bóng đá
6. Quản lý đội bóng
7. Quản lý lịch thi đấu

### 6.2. Các Use Case Phụ
1. Đăng ký / Đăng nhập tài khoản
2. Tìm kiếm và Lọc sân bóng theo tiêu chí
3. Xem thông tin chi tiết sân bóng & lịch trống
4. Đăng ký đội bóng tham gia giải
5. Xem lịch thi đấu & bảng xếp hạng giải đấu

---

## 7. Yêu cầu Kỹ thuật và Công nghệ (Stack)

- **Mô hình Kiến trúc:** MVC (Model - View - Controller).
- **Phía Máy chủ (Server / Backend):** Web API phát triển bằng **PHP** cùng FrameWork **Laravel**.
- **Phía Giao diện (Client / Frontend):** HTML, CSS, JS kết hợp Blade Template của Laravel.
- **Cơ sở dữ liệu:** MySQL (tuân thủ sơ đồ thiết kế Diagram chặt chẽ).
- **Môi trường triển khai:** Webserver **Apache**, chạy localhost.
- **Tích hợp mở rộng:** Sử dụng **Dialogflow AI** để tạo Chatbot hỗ trợ người dùng, nhúng trực tiếp dạng widget vào website.

---

## 8. Danh sách Hệ thống Giao diện (UI Pages)

1. **Trang chủ** (Hiển thị nổi bật các sân, giải đấu).
2. **Trang danh sách và chi tiết sân bóng** (Hỗ trợ tìm kiếm, lọc).
3. **Trang đặt sân** (Chọn ngày, khung giờ và thanh toán).
4. **Trang quản lý / Hiển thị lịch đặt sân**.
5. **Trang Đăng ký / Đăng nhập**.
6. **Trang quản lý giải đấu** (Thông tin giải, các đội góp mặt).
7. **Trang lịch thi đấu và Bảng xếp hạng**.
8. **Trang quản trị (Admin Dashboard)** (Bao gồm các Form Thêm/Sửa/Xóa thông tin sân bóng, giải đấu, đội bóng, lịch đặt sân).
