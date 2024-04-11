@extends('admin.layouts.main')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 ml-4 text-gray-800">Expenses</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Expense</li>
    </ol>
</div>

<div class="row justify-content-center">
    @if(Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif
    <div class="col-lg-6">
        <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card mb-6">
                <div class="card-header-sm py-3  text-white">
                    <h6 class="m-0 font-weight-bold">Update Expense</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="expense_name">Expense Name</label>
                            <input type="text" name="expense_name" value="{{ $expense->expense_name }}" class="form-control" placeholder="Enter the name of expense" required>
                            @error('expense_name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="amount">Amount (£)</label>
                            <input type="number" name="amount" value="{{ $expense->amount }}" class="form-control" step="0.01" placeholder="Enter amount in GBP" required>
                            @error('amount')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="category">Category</label>
                            <input type="text" name="category" value="{{ $expense->category }}" class="form-control" placeholder="Enter category" required>
                            @error('category')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" value="{{ $expense->date }}" class="form-control" required>
                            @error('date')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" class="form-control">{{ $expense->notes }}</textarea>
                        @error('notes')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
