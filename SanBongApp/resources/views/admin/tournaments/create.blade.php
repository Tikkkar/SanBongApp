@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>Tạo Giải Đấu Bóng Đá Mới</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ url('/admin/tournaments') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label fw-bold">Tên Giải Đấu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="logo" class="form-label fw-bold">Logo/Banner Giải</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label fw-bold">Ngày Khai Mạc <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label fw-bold">Ngày Bế Mạc (Dự kiến)</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="format" class="form-label fw-bold">Thể Thức Thi Đấu</label>
                            <select class="form-select @error('format') is-invalid @enderror" id="format" name="format">
                                <option value="Chia Bảng" {{ old('format') == 'Chia Bảng' ? 'selected' : '' }}>Chia Bảng & Đấu Chéo</option>
                                <option value="Vòng Tròn" {{ old('format') == 'Vòng Tròn' ? 'selected' : '' }}>Đấu Vòng Tròn (League)</option>
                                <option value="Loại Trực Tiếp" {{ old('format') == 'Loại Trực Tiếp' ? 'selected' : '' }}>Đấu Loại Trực Tiếp (Knockout)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="max_teams" class="form-label fw-bold">Quy Mô (Số Đội)</label>
                            <input type="number" class="form-control @error('max_teams') is-invalid @enderror" id="max_teams" name="max_teams" value="{{ old('max_teams') }}" placeholder="VD: 8, 16, 32">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pitch_type" class="form-label fw-bold">Loại Sân</label>
                            <select class="form-select @error('pitch_type') is-invalid @enderror" id="pitch_type" name="pitch_type">
                                <option value="">-- Chọn Loại Sân --</option>
                                <option value="Sân 5" {{ old('pitch_type') == 'Sân 5' ? 'selected' : '' }}>Sân 5 Người</option>
                                <option value="Sân 7" {{ old('pitch_type') == 'Sân 7' ? 'selected' : '' }}>Sân 7 Người</option>
                                <option value="Sân 11" {{ old('pitch_type') == 'Sân 11' ? 'selected' : '' }}>Sân 11 Người</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Mô tả Giải</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-bold">Trạng thái giải</label>
                            <select class="form-select" id="status" name="status">
                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="registration_deadline" class="form-label fw-bold">Hạn Chót Đăng Ký</label>
                            <input type="date" class="form-control @error('registration_deadline') is-invalid @enderror" id="registration_deadline" name="registration_deadline" value="{{ old('registration_deadline') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="registration_fee" class="form-label fw-bold">Lệ Phí Tham Gia (VNĐ)</label>
                            <input type="number" step="1000" class="form-control @error('registration_fee') is-invalid @enderror" id="registration_fee" name="registration_fee" value="{{ old('registration_fee') }}" placeholder="VD: 1000000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prize_pool" class="form-label fw-bold">Cơ Cấu Giải Thưởng</label>
                            <input type="text" class="form-control @error('prize_pool') is-invalid @enderror" id="prize_pool" name="prize_pool" value="{{ old('prize_pool') }}" placeholder="Cúp, Huy Chương, Hiện Kim...">
                        </div>
                    </div>
                    
                    <div class="mb-3 mt-2 form-check">
                        <input type="checkbox" class="form-check-input" id="requires_approval" name="requires_approval" value="1" {{ old('requires_approval', true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="requires_approval">Yêu cầu Admin phê duyệt khi có đội đăng ký tham gia</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold">Tạo Giải Đấu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
