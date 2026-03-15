@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Thêm Sân Bóng Mới</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ url('/admin/pitches') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Tên Sân Bóng</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price_per_hour" class="form-label fw-bold">Giá thuê mỗi trận (VNĐ)</label>
                        <input type="number" class="form-control @error('price_per_hour') is-invalid @enderror" id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour') }}" required min="0" step="1000">
                        @error('price_per_hour')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label fw-bold">Sức chứa (Ví dụ: sân 7, sân 11)</label>
                        <select class="form-select @error('capacity') is-invalid @enderror" id="capacity" name="capacity">
                            <option value="">-- Chọn Loại Sân --</option>
                            <option value="5" {{ old('capacity') == 5 ? 'selected' : '' }}>Sân 5 người</option>
                            <option value="7" {{ old('capacity') == 7 ? 'selected' : '' }}>Sân 7 người</option>
                            <option value="11" {{ old('capacity') == 11 ? 'selected' : '' }}>Sân 11 người</option>
                        </select>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-bold">Hình ảnh đại diện</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Hủy bỏ</a>
                        <button type="submit" class="btn btn-success px-5 fw-bold">Thêm Sân Bóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
