@extends('layouts.app')

@section('content')
<div class="row align-items-center mb-5">
    <div class="col-lg-6">
        <h1 class="display-4 fw-bold text-success mb-4">Đặt Sân Bóng Nhanh Chóng & Chuyên Nghiệp</h1>
        <p class="lead text-muted mb-4">Hệ thống đặt sân bóng tự động, minh bạch. Tìm kiếm đối tác, tổ chức và quản lý giải đấu một cách dễ dàng nhất.</p>
        <div class="mb-4">
            <h5 class="text-success"><i class="bi bi-telephone-fill me-2"></i>Hotline đặt sân: <span class="fw-bold">0336186131</span></h5>
        </div>
        <div class="d-grid gap-2 d-md-flex justify-content-md-start">
            <a href="{{ url('/pitches') }}" class="btn btn-success btn-lg px-4 me-md-2">Đặt Sân Ngay</a>
            <a href="{{ url('/tournaments') }}" class="btn btn-outline-secondary btn-lg px-4">Xem Giải Đấu</a>
        </div>
    </div>
    <div class="col-lg-6 mt-4 mt-lg-0">
        <img src="https://images.unsplash.com/photo-1579952363873-27f3bade9f55?q=80&w=1000&auto=format&fit=crop" class="img-fluid rounded shadow-lg" alt="Sân bóng đá">
    </div>
</div>

<div class="row text-center mt-5">
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body py-5">
                <i class="bi bi-search text-success" style="font-size: 3rem;"></i>
                <h3 class="h4 mt-3">Tìm Kiếm Dễ Dàng</h3>
                <p class="text-muted">Bộ lọc thông minh giúp bạn tìm được sân theo nhu cầu, ví trí và mức giá.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body py-5">
                <i class="bi bi-calendar-check text-success" style="font-size: 3rem;"></i>
                <h3 class="h4 mt-3">Đặt Lịch 24/7</h3>
                <p class="text-muted">Hệ thống hiển thị giờ thực tế tránh trùng lặp. Đặt sân bất cứ lúc nào, hỗ trợ thanh toán tại quầy.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body py-5">
                <i class="bi bi-trophy text-success" style="font-size: 3rem;"></i>
                <h3 class="h4 mt-3">Tổ Chức Giải Đấu</h3>
                <p class="text-muted">Tính năng đăng ký giải đấu dành cho đội bóng. Cập nhật bảng xếp hạng tự động.</p>
            </div>
        </div>
    </div>
</div>
@endsection
