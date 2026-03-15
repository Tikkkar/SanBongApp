@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Xếp Lịch Thi Đấu Mới</h4>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-secondary">Giải đấu: <strong>{{ $tournament->name }}</strong></div>

                <form method="POST" action="{{ url('/admin/tournaments/'.$tournament->id.'/matches') }}">
                    @csrf
                    
                    <div class="row mt-4">
                        <div class="col-md-5">
                            <label for="home_team_id" class="form-label fw-bold">Đội Nhà</label>
                            <select class="form-select @error('home_team_id') is-invalid @enderror" id="home_team_id" name="home_team_id" required>
                                <option value="">-- Chọn đội --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @error('home_team_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <h4 class="text-muted fw-bold mt-4">VS</h4>
                        </div>
                        
                        <div class="col-md-5">
                            <label for="away_team_id" class="form-label fw-bold">Đội Khách</label>
                            <select class="form-select @error('away_team_id') is-invalid @enderror" id="away_team_id" name="away_team_id" required>
                                <option value="">-- Chọn đội --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                            @error('away_team_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mt-4 mb-3 mx-auto justify-content-center">
                        <div class="col-md-5">
                            <label for="round_name" class="form-label fw-bold d-block text-center">Tên Vòng Đấu (Tùy chọn)</label>
                            <select name="round_name" id="round_name" class="form-select @error('round_name') is-invalid @enderror">
                                <option value="">-- Không xác định --</option>
                                <option value="Vòng Bảng" {{ old('round_name') == 'Vòng Bảng' ? 'selected' : '' }}>Vòng Bảng</option>
                                <option value="Vòng 1/8" {{ old('round_name') == 'Vòng 1/8' ? 'selected' : '' }}>Vòng 1/8</option>
                                <option value="Tứ Kết" {{ old('round_name') == 'Tứ Kết' ? 'selected' : '' }}>Tứ Kết</option>
                                <option value="Bán Kết" {{ old('round_name') == 'Bán Kết' ? 'selected' : '' }}>Bán Kết</option>
                                <option value="Tranh Hạng 3" {{ old('round_name') == 'Tranh Hạng 3' ? 'selected' : '' }}>Tranh Hạng 3</option>
                                <option value="Chung Kết" {{ old('round_name') == 'Chung Kết' ? 'selected' : '' }}>Chung Kết</option>
                            </select>
                            @error('round_name') <div class="invalid-feedback text-center">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3 w-50 mx-auto">
                        <label for="match_time" class="form-label fw-bold text-center d-block">Thời Gian Thi Đấu</label>
                        <input type="datetime-local" class="form-control text-center @error('match_time') is-invalid @enderror" id="match_time" name="match_time" value="{{ old('match_time') }}" required>
                        @error('match_time') <div class="invalid-feedback text-center">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                        <a href="{{ url('/tournaments/'.$tournament->id) }}" class="btn btn-outline-secondary">Trở về</a>
                        <button type="submit" class="btn btn-warning px-5 fw-bold">Lưu Lịch Thi Đấu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
