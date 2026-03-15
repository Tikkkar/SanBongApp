@extends('layouts.app')

@section('content')
<h2 class="fw-bold mb-4 text-success"><i class="bi bi-calendar-check me-2"></i>Lịch Đặt Của Tôi</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tên Sân</th>
                        <th>Giờ Bắt Đầu</th>
                        <th>Giờ Kết Thúc</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th class="text-end pe-4">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4 fw-bold text-success">{{ $booking->pitch->name }}</td>
                            <td>{{ $booking->start_time->format('d/m/Y H:i') }}</td>
                            <td>{{ $booking->end_time->format('d/m/Y H:i') }}</td>
                            <td class="fw-bold text-danger">{{ number_format($booking->total_price, 0, ',', '.') }}₫</td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Chờ Duyệt</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Đã Xác Nhận</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Đã Hủy</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                @if($booking->status === 'pending')
                                    <form action="{{ url('/bookings/'.$booking->id.'/cancel') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn này?')">Hủy</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Bạn chưa có lượt đặt sân nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
