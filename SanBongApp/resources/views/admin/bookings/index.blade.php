@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary"><i class="bi bi-calendar2-check-fill me-2"></i>Quản Lý Lịch Đặt Sân</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Trở Về Dashboard</a>
</div>

<!-- Bộ Lọc -->
<div class="card shadow-sm border-0 mb-4 bg-light">
    <div class="card-body">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="row align-items-end g-3">
            <div class="col-md-3">
                <label for="date" class="form-label fw-bold">Lọc theo Ngày</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
            </div>
            <div class="col-md-3">
                <label for="pitch_id" class="form-label fw-bold">Lọc theo Sân</label>
                <select name="pitch_id" id="pitch_id" class="form-select">
                    <option value="">-- Tất cả sân --</option>
                    @foreach($pitches as $pitch)
                        <option value="{{ $pitch->id }}" {{ request('pitch_id') == $pitch->id ? 'selected' : '' }}>
                            {{ $pitch->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label fw-bold">Trạng Thái</label>
                <select name="status" id="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ Duyệt</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã Duyệt</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã Hủy</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Lọc Kết Quả</button>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary w-100">Xóa Lọc</a>
            </div>
        </form>
    </div>
</div>

<!-- Bản dữ liệu đặt sân -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-4">Khách Hàng</th>
                        <th>Sân Bóng</th>
                        <th>Thời Gian Đặt</th>
                        <th>Tổng Thu</th>
                        <th>Trạng Thái</th>
                        <th class="text-end pe-4">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="text-start ps-4">
                                <div class="fw-bold">{{ $booking->customer_name ?? $booking->user->name }}</div>
                                <div class="small text-muted">{{ $booking->customer_phone ?? $booking->user->email }}</div>
                            </td>
                            <td class="fw-bold text-success">{{ $booking->pitch->name }}</td>
                            <td>
                                <div>{{ $booking->start_time->format('d/m/Y') }}</div>
                                <div class="fw-bold text-primary">{{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}</div>
                            </td>
                            <td class="fw-bold text-danger">{{ number_format($booking->total_price, 0, ',', '.') }}₫</td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ Duyệt</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-success">Đã Duyệt</span>
                                @else
                                    <span class="badge bg-danger">Đã Hủy</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($booking->status === 'pending')
                                    <form action="{{ url('/admin/bookings/'.$booking->id.'/approve') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Duyệt</button>
                                    </form>
                                @endif
                                
                                @if($booking->status !== 'cancelled')
                                    <form action="{{ url('/bookings/'.$booking->id.'/cancel') }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Hủy đơn đặt sân này?')">Từ chối</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2"></i>
                                <p class="mt-2 mb-0">Không tìm thấy lịch đặt sân nào phù hợp.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white pt-3 pb-1">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
