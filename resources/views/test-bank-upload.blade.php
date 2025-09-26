<!DOCTYPE html>
<html>
<head>
    <title>Test Bank Receipt Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Bank Receipt Upload</h1>
    <form id="testForm" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Bank Transaction Ref:</label>
            <input type="text" name="bank_transaction_ref" value="TEST-123456" required>
        </div>
        <div>
            <label>Transfer DateTime:</label>
            <input type="datetime-local" name="transfer_datetime" value="{{ now()->format('Y-m-d\\TH:i') }}" required>
        </div>
        <div>
            <label>Transfer Notes:</label>
            <textarea name="transfer_notes">Test upload</textarea>
        </div>
        <div>
            <label>Bank Receipt:</label>
            <input type="file" name="bank_receipt" accept="image/*,application/pdf" required>
        </div>
        <button type="button" onclick="uploadReceipt()">Upload Receipt</button>
    </form>

    <div id="result" style="margin-top: 20px;"></div>

    <script>
        async function uploadReceipt() {
            const form = document.getElementById('testForm');
            const formData = new FormData(form);
            const resultDiv = document.getElementById('result');
            
            resultDiv.innerHTML = 'Uploading...';

            try {
                const response = await fetch('/api/upload-bank-receipt', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const result = await response.json();
                
                resultDiv.innerHTML = `
                    <h3>Response (${response.status}):</h3>
                    <pre>${JSON.stringify(result, null, 2)}</pre>
                `;

            } catch (error) {
                resultDiv.innerHTML = `<h3>Error:</h3><pre>${error.message}</pre>`;
            }
        }
    </script>
</body>
</html>