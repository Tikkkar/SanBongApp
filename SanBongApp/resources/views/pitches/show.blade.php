@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/pitches') }}" class="text-success text-decoration-none">Sân bóng</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $pitch->name }}</li>
  </ol>
</nav>

<div class="row mt-4">
    <div class="col-md-8">
        <img src="{{ $pitch->image ?? 'https://images.unsplash.com/photo-1518605368461-1ee7c5122e28?q=80&w=800&auto=format&fit=crop' }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 400px; object-fit: cover;" alt="Sân bóng">
        
        <h2 class="fw-bold mt-4">
            {{ $pitch->name }}
            @if($pitch->capacity)
                <span class="badge bg-secondary ms-2 align-middle">Sân {{ $pitch->capacity }}</span>
            @endif
        </h2>
        <p class="text-muted"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Số 1, Đường Chuyên Nghiệp, Hà Nội</p>
        
        <h5 class="fw-bold mt-4">Mô tả chi tiết:</h5>
        <p>{{ $pitch->description ?? 'Được trang bị cỏ nhân tạo đạt chuẩn quốc tế, đèn chiếu sáng ban đêm cực tốt thích hợp cho các giải đấu phong trào.' }}</p>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 sticky-top" style="top: 100px; max-height: calc(100vh - 120px); overflow-y: auto;">
            <div class="card-body p-4">
                <h4 class="fw-bold text-center mb-0 text-success">{{ number_format($pitch->price_per_hour, 0, ',', '.') }}₫ <small class="text-muted fs-6 fw-normal">/ trận (45p)</small></h4>
                <hr>
                <form action="{{ url('/bookings/create') }}" method="GET">
                    <input type="hidden" name="pitch_id" value="{{ $pitch->id }}">
                    <div class="d-grid mt-4">
                        @auth
                            <button type="submit" class="btn btn-success btn-lg">Đặt sân ngay</button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg">Đăng nhập để đặt sân</a>
                        @endauth
                    </div>
                </form>
                
                <div class="mt-4 text-center border-bottom pb-4">
                    <p class="text-muted small mb-0"><i class="bi bi-shield-check text-success me-1"></i> Hoàn tiền nếu sân trùng giờ.</p>
                </div>

                <div class="mt-4">
                    <h5 class="fw-bold mb-3 text-center">Bảng Giờ Trống</h5>
                    
                    <form action="{{ url('/pitches/'.$pitch->id) }}" method="GET" class="mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light fw-bold">Ngày:</span>
                            <input type="date" name="date" class="form-control" value="{{ $date }}" min="{{ date('Y-m-d') }}" onchange="this.form.submit()">
                        </div>
                    </form>

                    <div class="row g-2">
                        @foreach($timeSlots as $slot)
                            <div class="col-4">
                                @if($slot['is_booked'] || $slot['is_past'])
                                    <div class="border rounded p-2 text-center bg-light text-muted position-relative" style="font-size: 0.85rem; cursor: not-allowed;">
                                        <div class="fw-bold text-decoration-line-through">{{ $slot['time'] }}</div>
                                        <small>{{ $slot['is_past'] && !$slot['is_booked'] ? 'Đã qua' : 'Đã đặt' }}</small>
                                    </div>
                                @else
                                    <form action="{{ url('/bookings/create') }}" method="GET">
                                        <input type="hidden" name="pitch_id" value="{{ $pitch->id }}">
                                        <input type="hidden" name="booking_date" value="{{ $date }}">
                                        <input type="hidden" name="time_slot" value="{{ $slot['time'] }}">
                                        <button type="submit" class="btn btn-outline-success w-100 p-2 text-center position-relative" style="font-size: 0.85rem;" title="Bấm để đặt giờ này">
                                            @if($slot['price_hour'] != $pitch->price_per_hour)
                                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem;">
                                                    <i class="bi bi-star-fill"></i>
                                                </span>
                                            @endif
                                            <div class="fw-bold">{{ $slot['time'] }}</div>
                                            <small class="fw-bold text-danger">{{ number_format($slot['price'], 0, ',', '.') }}₫</small>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
