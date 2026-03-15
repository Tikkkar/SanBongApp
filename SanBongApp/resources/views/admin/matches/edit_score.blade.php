@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white text-center">
                <h4 class="mb-0">Cập Nhật Máy Tỉ Số</h4>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <p class="text-muted mb-1">{{ $match->tournament->name }}</p>
                    @if($match->round_name)
                        <p class="text-info fw-bold mb-1">{{ $match->round_name }}</p>
                    @endif
                    <p class="mb-0 fw-bold"><i class="bi bi-clock"></i> {{ $match->match_time->format('H:i - d/m/Y') }}</p>
                </div>

                <form method="POST" action="{{ url('/admin/matches/'.$match->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row align-items-center justify-content-center text-center px-4 mt-2">
                        <div class="col-5">
                            <h5 class="fw-bold text-truncate mb-1">{{ $match->homeTeam->name }}</h5>
                            @if($match->homeTeam->group_name)
                                <span class="badge bg-secondary">Bảng {{ $match->homeTeam->group_name }}</span>
                            @endif
                            <input type="number" class="form-control form-control-lg text-center fs-2 text-danger fw-bold mt-3 @error('home_score') is-invalid @enderror" id="home_score" name="home_score" value="{{ old('home_score', $match->home_score) }}" required min="0">
                        </div>
                        <div class="col-2">
                            <span class="fs-4 text-muted">-</span>
                        </div>
                        <div class="col-5">
                            <h5 class="fw-bold text-truncate mb-1">{{ $match->awayTeam->name }}</h5>
                            @if($match->awayTeam->group_name)
                                <span class="badge bg-secondary">Bảng {{ $match->awayTeam->group_name }}</span>
                            @endif
                            <input type="number" class="form-control form-control-lg text-center fs-2 text-primary fw-bold mt-3 @error('away_score') is-invalid @enderror" id="away_score" name="away_score" value="{{ old('away_score', $match->away_score) }}" required min="0">
                        </div>
                    </div>

                    <div class="mb-4 mx-4">
                        <label class="form-label fw-bold">Trạng thái trận đấu</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="scheduled" {{ old('status', $match->status) == 'scheduled' ? 'selected' : '' }}>Sắp diễn ra</option>
                            <option value="playing" {{ old('status', $match->status) == 'playing' ? 'selected' : '' }}>Đang thi đấu</option>
                            <option value="finished" {{ old('status', $match->status) == 'finished' ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mt-4 mx-4">
                        <button type="submit" class="btn btn-dark btn-lg">Lưu Kết Quả</button>
                        <a href="{{ url('/tournaments/'.$match->tournament_id) }}" class="btn btn-link text-muted mt-2">Hủy bỏ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
