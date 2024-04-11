@extends('admin.layouts.main')

@section('content')
<div class="container-fluid" style="min-height: 100vh;">
    <div class="card shadow-lg" style="max-width: 800px; margin: auto;">
        <div class="card-header-sm text-white">
            <h5 class="mb-0">Update Income</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('income.update', $income->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="source">Source</label>
                    <input type="text" class="form-control" id="source" name="source" value="{{ $income->source }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ $income->date->format('Y-m-d') }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="value">Value (£)</label>
                    <input type="number" class="form-control" id="value" name="value" value="{{ $income->value }}" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('income.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
