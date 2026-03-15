@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="bi bi-calendar-plus"></i> Đặt Sân Thủ Công (Khách Vãng Lai)</h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted">Đơn đặt sân này sẽ được lưu ngay lập tức với trạng thái <strong>Đã duyệt</strong> mà không cần khách hàng tạo tài khoản.</p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- BỘ LỌC CHỌN SÂN VÀ NGÀY -->
                <form action="{{ route('admin.bookings.create') }}" method="GET" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="pitch_id" class="form-label fw-bold">Chọn Sân Bóng <span class="text-danger">*</span></label>
                            <select name="pitch_id" id="pitch_id" class="form-select border-info" onchange="this.form.submit()" required>
                                <option value="">-- Chọn Sân Trước --</option>
                                @foreach($pitches as $pitch)
                                    <option value="{{ $pitch->id }}" {{ request('pitch_id') == $pitch->id ? 'selected' : '' }}>
                                        {{ $pitch->name }} (Mặc định: {{ number_format($pitch->price_per_hour, 0, ',', '.') }}₫)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label fw-bold">Chọn Ngày <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control border-info" value="{{ $date }}" min="{{ date('Y-m-d') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                </form>

                <hr class="mb-4">

                @if($selectedPitch)
                    <form action="{{ route('admin.bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="pitch_id" value="{{ $selectedPitch->id }}">
                        <input type="hidden" name="booking_date" value="{{ $date }}">
                        
                        <label class="form-label fw-bold text-success">Chọn Khung Giờ Mới Bắt Đầu ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }})</label>
                        <div class="row g-2 mb-4">
                            @foreach($timeSlots as $slot)
                                <div class="col-4 col-md-3 col-lg-2">
                                    <input type="radio" class="btn-check" name="time_slot" id="slot_{{ $loop->index }}" value="{{ $slot['time'] }}" data-price="{{ $slot['price'] }}" {{ $slot['is_booked'] || $slot['is_past'] ? 'disabled' : '' }} required>
                                    <label class="btn btn-outline-success w-100 p-2 {{ $slot['is_booked'] || $slot['is_past'] ? 'disabled opacity-50 bg-light border-light text-muted' : '' }}" for="slot_{{ $loop->index }}">
                                        <div class="fw-bold">{{ $slot['time'] }}</div>
                                        <small>{{ number_format($slot['price'], 0, ',', '.') }}₫</small>
                                        @if($slot['is_booked'])
                                            <div style="font-size: 0.7em;" class="text-danger mt-1">Đã đặt</div>
                                        @elseif($slot['is_past'])
                                            <div style="font-size: 0.7em;" class="text-muted mt-1">Đã qua</div>
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label fw-bold">Tên Khách Hàng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required placeholder="Vd: Nguyễn Văn A">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label fw-bold">Số Điện Thoại</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Tùy chọn">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="total_price" class="form-label fw-bold">Tổng tiền thu (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" step="1000" class="form-control bg-light" id="total_price" name="total_price" value="{{ old('total_price') }}" required>
                            <small class="text-primary">Giá tiền sẽ tự cập nhật khi chọn giờ. Admin có thể tự sửa lại số tiền thực tế thu khách.</small>
                        </div>

                        <div class="d-flex justify-content-between text-end mt-4">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4">Hủy bỏ</a>
                            <button type="submit" class="btn btn-info text-white px-5"><i class="bi bi-check-circle"></i> Trực Tiếp Lịch</button>
                        </div>
                    </form>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const radioButtons = document.querySelectorAll('input[name="time_slot"]');
                            const priceInput = document.getElementById('total_price');
                            
                            radioButtons.forEach(radio => {
                                radio.addEventListener('change', function() {
                                    if(this.checked) {
                                        priceInput.value = this.dataset.price;
                                    }
                                });
                            });
                        });
                    </script>
                @else
                    <div class="text-center py-5 text-muted bg-light rounded border border-info border-opacity-25">
                        <i class="bi bi-arrow-up-circle fs-1 text-info"></i>
                        <h5 class="mt-3 mb-1 text-dark">Hãy chọn sân bóng trước</h5>
                        <p class="mb-0">Vui lòng chọn <strong>Sân Bóng</strong> ở bộ lọc phía trên cùng để hệ thống quét và hiển thị các khung giờ trống.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
