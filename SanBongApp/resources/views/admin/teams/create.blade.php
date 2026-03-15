@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-shield-plus"></i> Thêm Đội Bóng Thủ Công</h4>
            </div>
            <div class="card-body p-4">
                <p class="text-muted">Đội bóng được thêm bởi Admin sẽ tự động ở trạng thái <strong>Đã duyệt</strong>.</p>
                <form action="{{ route('admin.teams.store', $tournament->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Tên đội bóng <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="group_name" class="form-label fw-bold">Bảng đấu (Tùy chọn)</label>
                        <input type="text" class="form-control @error('group_name') is-invalid @enderror" id="group_name" name="group_name" value="{{ old('group_name') }}" placeholder="Ví dụ: A, B, C... hoặc để trống">
                        <div class="form-text">Nhập ký hiệu hoặc tên bảng đấu nếu giải đấu có chia bảng.</div>
                        @error('group_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_phone" class="form-label fw-bold">Số điện thoại liên hệ (Đại diện/Đội trưởng)</label>
                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="Không bắt buộc">
                        @error('contact_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="logo" class="form-label fw-bold">Logo Đội Bóng</label>
                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*">
                        <div class="form-text">Định dạng JPG, PNG. Tối đa 2MB. Vui lòng để trống nếu không có.</div>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between text-end mt-4">
                        <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-outline-secondary px-4">Hủy bỏ</a>
                        <button type="submit" class="btn btn-primary px-5">Thêm Đội Bóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
