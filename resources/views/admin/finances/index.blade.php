@extends('admin.layouts.main')

@section('content')
<div class="container-fluid" style="min-height: 100vh;">
    <!-- Finance Table Card -->
    <div class="card shadow-lg mb-4" style="max-width: 800px; margin: auto;">
        <div class="card-header-sm text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Finance</h5>
            <button class="btn btn-success" onclick="toggleAddForm()">+ Add Finance</button>
        </div>
        <div class="card-body">
            @if($finances->isEmpty())
                <p class="text-center">No finance logged yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Loan Type</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($finances as $finance)
                                <tr>
                                    <td>{{ $finance->expected_date }}</td>
                                    <td>{{ $finance->amount }}</td>
                                    <td>{{ $finance->loan_type }}</td>
                                    <td>{{ $finance->status }}</td>
                                    <td>{{ $finance->notes }}</td>
                                    <td>
                                        <button onclick='showUpdateForm(@json($finance))' class="btn btn-sm btn-success">Edit</button>
                                        <form action="{{ route('finances.destroy', $finance->id) }}" method="POST" style="display:inline;">
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

    <!-- Add Finance Form Card -->
    <div id="addFinanceForm" style="display: none; max-width: 800px; margin: auto;" class="card shadow-lg mb-4">
        <div class="card-header-sm text-white">
            <h5 class="mb-0">Add Finance</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('finances.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="expected_date">Expected Date</label>
                        <input type="date" class="form-control" id="expected_date" name="expected_date" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="loan_type">Loan Type</label>
                        <select class="form-control" id="loan_type" name="loan_type">
                            <option value="Tuition Fee">Tuition Fee</option>
                            <option value="Maintenance Loan">Maintenance Loan</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Approved">Approved</option>
                            <option value="Not-Approved">Not-Approved</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <button type="button" class="btn btn-secondary" onclick="toggleAddForm()">Cancel</button>
            </form>
        </div>
    </div>
    <!-- Update Finance Form Card -->
    <div id="updateFinanceForm" style="display: none; max-width: 800px; margin: auto;" class="card shadow-lg mb-4">
        <div class="card-header-sm text-white">
            <h5 class="mb-0">Update Finance</h5>
        </div>
        <div class="card-body">
            <form method="POST" id="financeUpdateForm">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="update_expected_date">Expected Date</label>
                        <input type="date" class="form-control" id="update_expected_date" name="expected_date" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="update_amount">Amount</label>
                        <input type="number" class="form-control" id="update_amount" name="amount" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="update_loan_type">Loan Type</label>
                        <select class="form-control" id="update_loan_type" name="loan_type">
                            <option value="Tuition Fee">Tuition Fee</option>
                            <option value="Maintenance Loan">Maintenance Loan</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="update_status">Status</label>
                        <select class="form-control" id="update_status" name="status">
                            <option value="Approved">Approved</option>
                            <option value="Not-Approved">Not-Approved</option>
                            <option value="Cancelled">Cancelled</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="update_notes">Notes</label>
                        <textarea class="form-control" id="update_notes" name="notes"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleAddForm() {
        var form = document.getElementById('addFinanceForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        if (form.style.display === 'block') {
            form.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function showUpdateForm(finance) {
        // Display the update form
        document.getElementById('updateFinanceForm').style.display = 'block';

        // Scroll the update form into view
        document.getElementById('updateFinanceForm').scrollIntoView({ behavior: 'smooth' });

        // Populate the update form with the finance data
        document.getElementById('update_expected_date').value = finance.expected_date;
        document.getElementById('update_amount').value = finance.amount;
        document.getElementById('update_loan_type').value = finance.loan_type;
        document.getElementById('update_status').value = finance.status;
        document.getElementById('update_notes').value = finance.notes;

        // Update the form action to include the finance ID
        document.getElementById('financeUpdateForm').action = `/finances/${finance.id}`;
    }
</script>
@endsection
