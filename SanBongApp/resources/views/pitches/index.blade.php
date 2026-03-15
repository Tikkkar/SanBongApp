@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-success">Danh Sách Sân Bóng</h2>
    <div class="w-50 w-md-25">
        <form action="{{ url('/pitches') }}" method="GET" class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên sân..." value="{{ request('search') }}">
            <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
</div>

<div class="row">
    @forelse($pitches as $pitch)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="{{ $pitch->image ?? 'https://images.unsplash.com/photo-1518605368461-1ee7c5122e28?q=80&w=400&auto=format&fit=crop' }}" class="card-img-top" alt="Hình sân" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title fw-bold">
                        {{ $pitch->name }}
                        @if($pitch->capacity)
                            <span class="badge bg-secondary ms-1 fs-6">Sân {{ $pitch->capacity }}</span>
                        @endif
                    </h5>
                    <p class="card-text text-muted text-truncate">{{ $pitch->description ?? 'Sân cỏ nhân tạo chất lượng cao, thoát nước tốt.' }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="fs-5 fw-bold text-danger">{{ number_format($pitch->price_per_hour, 0, ',', '.') }}₫/giờ</span>
                        <a href="{{ url('/pitches/'.$pitch->id) }}" class="btn btn-sm btn-success px-3">Chi tiết & Đặt sân</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted py-5">
            <i class="bi bi-x-circle" style="font-size: 3rem;"></i>
            <p class="mt-3">Hiện chưa có sân bóng nào hoạt động.</p>
        </div>
    @endforelse
</div>
@endsection
