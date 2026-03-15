@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-warning"><i class="bi bi-layout-wtf me-2"></i>Bảng Điều Khiển Quản Trị Hệ Thống</h2>
    <div>
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-info text-white me-2"><i class="bi bi-calendar-plus"></i> Đặt Sân Thủ Công</a>
        <a href="{{ route('admin.pitches.create') }}" class="btn btn-success me-2"><i class="bi bi-plus-lg"></i> Thêm Sân</a>
        <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary"><i class="bi bi-trophy"></i> Giải Đấu</a>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm border-0 h-100">
            <div class="card-body p-4 text-center">
                <i class="bi bi-currency-dollar" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ number_format($totalRevenue, 0, ',', '.') }}₫</h3>
                <p class="mb-2">Doanh Thu Đặt Sân</p>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex justify-content-center mt-2 position-relative z-index-1">
                    <input type="date" name="revenue_date" class="form-control form-control-sm border-0 w-auto text-success bg-white fw-bold me-1" value="{{ $revenueDate }}" onchange="this.form.submit()" style="line-height:1; min-height:0; padding:0.25rem 0.5rem;" title="Lọc doanh thu theo ngày">
                    @if($revenueDate)
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light text-success px-2 py-0 d-flex align-items-center"><i class="bi bi-x"></i></a>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm border-0 h-100 position-relative">
            <div class="card-body p-4 text-center">
                <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{\App\Models\Booking::count()}}</h3>
                <p class="mb-0">Tổng Lượt Đặt Sân</p>
                <a href="{{ route('admin.bookings.index') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm border-0 h-100">
            <div class="card-body p-4 text-center position-relative">
                <i class="bi bi-people" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{\App\Models\User::count()}}</h3>
                <p class="mb-0">Người Dùng</p>
                <a href="{{ route('admin.users.index') }}" class="stretched-link"></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary shadow-sm border-0 h-100">
            <div class="card-body p-4 text-center">
                <i class="bi bi-trophy" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{\App\Models\Tournament::count()}}</h3>
                <p class="mb-0">Giải Đấu Đang Mở</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 mt-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-success mb-0"><i class="bi bi-geo-alt me-2"></i>Quản Lý Sân Bóng</h5>
                <a href="{{ url('/pitches') }}" class="btn btn-sm btn-outline-success">Xem Tất Cả Sân</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start ps-4">Sân Bóng</th>
                                <th>Loại Sân</th>
                                <th>Giá Gốc (VNĐ/trận)</th>
                                <th>Khung Giờ Đặc Biệt</th>
                                <th>Trạng Thái</th>
                                <th class="text-end pe-4">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Pitch::latest()->get() as $pitch)
                                <tr>
                                    <td class="text-start ps-4 fw-bold text-success">{{ $pitch->name }}</td>
                                    <td>{{ $pitch->capacity ? 'Sân ' . $pitch->capacity : 'Chưa nhập' }}</td>
                                    <td class="fw-bold">{{ number_format($pitch->price_per_hour, 0, ',', '.') }}₫</td>
                                    <td>
                                        <a href="{{ url('/admin/pitches/'.$pitch->id.'/prices') }}" class="btn btn-sm btn-info text-dark fw-bold">
                                            <i class="bi bi-clock-history"></i> Quản lý Giá ({{ $pitch->customPrices()->count() }})
                                        </a>
                                    </td>
                                    <td>
                                        @if($pitch->is_active)
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Tạm đóng</span>
                                        @endif
                                        <form action="{{ route('admin.pitches.toggle-active', $pitch->id) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-light border p-1" title="Đổi trạng thái">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ url('/admin/pitches/'.$pitch->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Sửa</a>
                                        <form action="{{ url('/admin/pitches/'.$pitch->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Bạn có chắc muốn xóa sân bóng này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="py-4 text-muted">Chưa có sân bóng nào.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 mt-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-success mb-0"><i class="bi bi-bell me-2"></i>Yêu Cầu Đặt Sân Cần Duyệt</h5>
                <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-success">Xem Tất Cả Lịch</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Khách Hàng</th>
                                <th>Sân Bóng</th>
                                <th>Thời Gian Đặt</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th class="text-end pe-4">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Booking::with('user', 'pitch')->where('status', 'pending')->latest()->take(10)->get() as $booking)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $booking->user->name }}</div>
                                        <div class="text-muted small">{{ $booking->user->email }}</div>
                                    </td>
                                    <td class="fw-bold text-success">{{ $booking->pitch->name }}</td>
                                    <td>
                                        <div>{{ $booking->start_time->format('d/m/Y H:i') }}</div>
                                        <div>đến {{ $booking->end_time->format('H:i') }}</div>
                                    </td>
                                    <td class="fw-bold text-danger">{{ number_format($booking->total_price, 0, ',', '.') }}₫</td>
                                    <td><span class="badge bg-warning text-dark">Chờ Duyệt</span></td>
                                    <td class="text-end pe-4">
                                        <form action="{{ url('/admin/bookings/'.$booking->id.'/approve') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Duyệt</button>
                                        </form>
                                        <form action="{{ url('/bookings/'.$booking->id.'/cancel') }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hủy đơn đặt sân này?')">Từ chối</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4 text-muted">Không có yêu cầu đặt sân chờ duyệt.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 mt-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-trophy me-2"></i>Quản Lý Giải Đấu</h5>
                <a href="{{ url('/tournaments') }}" class="btn btn-sm btn-outline-primary">Xem Tất Cả Giải Đấu</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tên Giải Đấu</th>
                                <th>Khởi Tranh</th>
                                <th>Trạng Thái</th>
                                <th class="text-end pe-4">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Tournament::latest()->get() as $tournament)
                                <tr>
                                    <td class="ps-4 fw-bold text-primary">{{ $tournament->name }}</td>
                                    <td>
                                        <div>{{ $tournament->start_date->format('d/m/Y') }}</div>
                                        @if($tournament->end_date)
                                            <div class="text-muted small">đến {{ $tournament->end_date->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($tournament->status === 'upcoming')
                                            <span class="badge bg-secondary">Sắp diễn ra</span>
                                        @elseif($tournament->status === 'ongoing')
                                            <span class="badge bg-success">Đang diễn ra</span>
                                        @else
                                            <span class="badge bg-danger">Đã kết thúc</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ url('/admin/tournaments/'.$tournament->id.'/edit') }}" class="btn btn-sm btn-outline-secondary">Sửa</a>
                                        <form action="{{ url('/admin/tournaments/'.$tournament->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Bạn có chắc muốn xóa giải đấu này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có giải đấu nào được lưu.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
