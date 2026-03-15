@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white border-0 mt-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Chỉnh Sửa Sân Bóng</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ url('/admin/pitches/'.$pitch->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Tên Sân Bóng</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pitch->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price_per_hour" class="form-label fw-bold">Giá thuê mỗi trận (VNĐ)</label>
                        <input type="number" class="form-control @error('price_per_hour') is-invalid @enderror" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour', $pitch->price_per_hour) }}" required min="0" step="1000">
                        @error('price_per_hour')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label fw-bold">Sức chứa (Ví dụ: sân 7, sân 11)</label>
                        <select class="form-select @error('capacity') is-invalid @enderror" id="capacity" name="capacity">
                            <option value="">-- Chọn Loại Sân --</option>
                            <option value="5" {{ old('capacity', $pitch->capacity) == 5 ? 'selected' : '' }}>Sân 5 người</option>
                            <option value="7" {{ old('capacity', $pitch->capacity) == 7 ? 'selected' : '' }}>Sân 7 người</option>
                            <option value="11" {{ old('capacity', $pitch->capacity) == 11 ? 'selected' : '' }}>Sân 11 người</option>
                        </select>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $pitch->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Hình ảnh đại diện mới (Bỏ trống nếu không thay đổi)</label>
                        @if($pitch->image)
                            <div class="mb-2">
                                <img src="{{ $pitch->image }}" alt="{{ $pitch->name }}" class="img-thumbnail" style="max-height: 150px;">
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $pitch->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="is_active">Hoạt động (Cho phép khách đặt sân)</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-secondary">Trở về Dashboard</a>
                        <button type="submit" class="btn btn-success px-5 fw-bold"><i class="bi bi-save me-1"></i> Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
