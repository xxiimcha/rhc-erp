<div class="modal fade" id="addSSSModal" tabindex="-1" aria-labelledby="addSSSModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addSSSModalLabel">Add SSS Bracket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Range From</label>
                    <input type="number" name="range_from" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label>Range To</label>
                    <input type="number" name="range_to" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label>Total Contribution</label>
                    <input type="number" name="total_contribution" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label>Employer Share</label>
                    <input type="number" name="employer_share" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label>Employee Share</label>
                    <input type="number" name="employee_share" class="form-control" step="0.01" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Bracket</button>
            </div>
        </form>
    </div>
</div>
