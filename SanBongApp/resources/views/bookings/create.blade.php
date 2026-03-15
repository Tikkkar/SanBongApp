@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Xác Nhận Đặt Sân</h4>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <strong>Đang đặt sân:</strong> <span class="text-dark fw-bold">{{ $pitch->name }}</span><br>
                    <strong>Giá dự kiến (1 trận):</strong> <span id="display_price" class="text-danger fw-bold fs-5">{{ number_format($pitch->price_per_hour, 0, ',', '.') }}₫</span>
                </div>

                <form method="POST" action="{{ url('/bookings') }}">
                    @csrf
                    <input type="hidden" name="pitch_id" value="{{ $pitch->id }}">
                    
                    <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2 text-success">Thông tin liên hệ</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label fw-bold">Tên người đặt</label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', Auth::user()->name) }}" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_phone" class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" placeholder="VD: 0987654321" required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2 text-success">Khung giờ đặt (45 phút)</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="booking_date" class="form-label fw-bold">Ngày Đá</label>
                            <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date', request('booking_date', date('Y-m-d'))) }}" required min="{{ date('Y-m-d') }}">
                            @error('booking_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="time_slot" class="form-label fw-bold">Khung Giờ (Có Sẵn)</label>
                            <!-- Generate 45 min intervals from 06:00 to 22:30 -->
                            <select class="form-select @error('time_slot') is-invalid @enderror" id="time_slot" name="time_slot" required>
                                <option value="">-- Chọn khung giờ --</option>
                                @php
                                    $start = strtotime('06:00');
                                    $end = strtotime('22:30');
                                @endphp
                                @while($start <= $end)
                                    @php
                                        $slot = date('H:i', $start);
                                    @endphp
                                    <option value="{{ $slot }}" {{ old('time_slot', request('time_slot')) == $slot ? 'selected' : '' }}>
                                        Nhận sân lúc: {{ $slot }}
                                    </option>
                                    @php
                                        $start = strtotime('+45 minutes', $start);
                                    @endphp
                                @endwhile
                            </select>
                            @error('time_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-1 d-block">Trận đấu kéo dài đúng 45 phút.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ url('/pitches/'.$pitch->id) }}" class="btn btn-outline-secondary">Trở về</a>
                        <button type="submit" class="btn btn-success px-5 fw-bold"><i class="bi bi-check2-circle me-1"></i> Xác Nhận Đặt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const timeSlotSelect = document.getElementById('time_slot');
        const displayPrice = document.getElementById('display_price');
        
        const basePriceHour = {{ $pitch->price_per_hour }};
        const customPrices = {!! $pricesJson !!};
        
        function updatePrice() {
            const selectedTime = timeSlotSelect.value;
            if (!selectedTime) {
                displayPrice.innerText = new Intl.NumberFormat('vi-VN').format(basePriceHour) + '₫';
                return;
            }
            
            // Format selected time as H:i:s
            const timeStr = selectedTime + ':00';
            let applicablePrice = basePriceHour;
            
            // Check if falls in any custom bracket
            for (let i = 0; i < customPrices.length; i++) {
                if (timeStr >= customPrices[i].start && timeStr < customPrices[i].end) {
                    applicablePrice = customPrices[i].price;
                    break;
                }
            }
            
            // Price is flat per match
            const finalPrice = applicablePrice;
            displayPrice.innerText = new Intl.NumberFormat('vi-VN').format(finalPrice) + '₫';
        }
        
        timeSlotSelect.addEventListener('change', updatePrice);
        
        // Trigger initial calculation if a slot is pre-selected
        if (timeSlotSelect.value) {
            updatePrice();
        }
    });
</script>
@endsection
