@extends('layouts.app')

@section('content')
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-success text-decoration-none">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/pitches/'.$pitch->id) }}" class="text-success text-decoration-none">{{ $pitch->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Quản Lý Giá Cấu Hình</li>
  </ol>
</nav>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Giá Theo Khung Giờ: {{ $pitch->name }}</h5>
                <span class="badge bg-light text-dark">Giá gốc: {{ number_format($pitch->price_per_hour, 0, ',', '.') }}₫/trận</span>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <div class="row">
                    <!-- Form Thêm Giá Mới -->
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Thêm Khung Giờ Mới</h6>
                                <form method="POST" action="{{ url('/admin/pitches/'.$pitch->id.'/prices') }}">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Giờ Bắt Đầu</label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Giờ Kết Thúc</label>
                                        <input type="time" name="end_time" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Giá Thuê Mới (VNĐ/trận)</label>
                                        <input type="number" name="price_per_hour" class="form-control" min="0" step="1000" required>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-success w-100">Thêm Cấu Hình Điểm Bán</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bảng Danh Sách Khung Giờ -->
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Khung Giờ</th>
                                        <th>Giá Áp Dụng (VNĐ/trận)</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pitch->customPrices()->orderBy('start_time')->get() as $priceConf)
                                        <tr>
                                            <td class="fw-bold text-primary">
                                                {{ \Carbon\Carbon::parse($priceConf->start_time)->format('H:i') }}
                                                <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                                {{ \Carbon\Carbon::parse($priceConf->end_time)->format('H:i') }}
                                            </td>
                                            <td class="fw-bold text-danger">{{ number_format($priceConf->price_per_hour, 0, ',', '.') }}₫</td>
                                            <td>
                                                <form action="{{ url('/admin/pitches/'.$pitch->id.'/prices/'.$priceConf->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa khung giờ này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted py-4">Chưa có khung giờ đặc biệt nào. Sân đang áp dụng Giá gốc cho toàn bộ thời gian.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
