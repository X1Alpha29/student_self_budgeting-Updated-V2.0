@extends('admin.layouts.main')

@section('content')
<div class="container-fluid" style="min-height: 100vh;">
    <div class="card shadow-lg mb-4" style="max-width: 800px; margin: auto;">
        <div class="card-header-sm text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Income</h5>
            <button class="btn btn-success" onclick="toggleForm('addIncomeForm')">+ Add Income</button>
        </div>
        <div class="card-body">
            @if($incomes->isEmpty())
                <p class="text-center">No income logged yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Date</th>
                                <th>Value (£)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incomes as $income)
                                <tr>
                                    <td>{{ $income->source }}</td>
                                    <td>{{ $income->date }}</td>
                                    <td>£{{ number_format($income->value, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" onclick="toggleForm('editIncomeForm-{{ $income->id }}')">Edit</button>
                                        <form action="{{ route('income.destroy', $income->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div id="addIncomeForm" style="display: none; max-width: 800px; margin: auto;" class="card shadow-lg mb-4">
        <div class="card-body">
            <form action="{{ route('income.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="source">Source</label>
                    <input type="text" class="form-control" id="source" name="source" required>
                </div>
                <div class="form-group mb-3">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group mb-3">
                    <label for="value">Value (£)</label>
                    <input type="number" class="form-control" id="value" name="value" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm('addIncomeForm')">Cancel</button>
            </form>
        </div>
    </div>

    @foreach($incomes as $income)
    <div id="editIncomeForm-{{ $income->id }}" style="display: none; max-width: 800px; margin: auto;" class="card shadow-lg mb-4">
        <div class="card-body">
            <form action="{{ route('income.update', $income->id) }}" method="POST">
                @csrf
                @method('PUT')
                <!-- Edit Form Fields Here -->
                <div class="form-group mb-3">
                    <label for="source">Source</label>
                    <input type="text" class="form-control" name="source" value="{{ $income->source }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" name="date" value="{{ $income->date }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="value">Value (£)</label>
                    <input type="number" class="form-control" name="value" value="{{ $income->value }}" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" onclick="toggleForm('editIncomeForm-{{ $income->id }}')">Cancel</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<script>
    function toggleForm(formId) {
        var form = document.getElementById(formId);
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
