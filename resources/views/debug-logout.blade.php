<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logout Debug Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .test-form { margin: 20px 0; padding: 20px; border: 1px solid #ddd; background: #f9f9f9; }
        .button { padding: 10px 20px; margin: 5px; border: none; cursor: pointer; }
        .logout-btn { background: #dc3545; color: white; }
        .test-btn { background: #007bff; color: white; }
        .success { color: green; }
        .error { color: red; }
        #output { margin-top: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
    <h1>Logout Debug Test Page</h1>
    
    <div id="status">
        @auth
            <p class="success">✅ User is authenticated: {{ Auth::user()->name }} ({{ Auth::user()->role ?? 'user' }})</p>
        @else
            <p class="error">❌ User is not authenticated</p>
        @endauth
    </div>

    <div class="test-form">
        <h3>Test 1: Standard Form Logout</h3>
        <form method="POST" action="{{ route('logout') }}" id="standard-logout">
            @csrf
            <button type="submit" class="button logout-btn">Standard Logout</button>
        </form>
    </div>

    <div class="test-form">
        <h3>Test 2: AJAX Logout</h3>
        <button type="button" class="button test-btn" onclick="testAjaxLogout()">AJAX Logout Test</button>
    </div>

    <div class="test-form">
        <h3>Test 3: Check CSRF Token</h3>
        <button type="button" class="button test-btn" onclick="checkCSRF()">Check CSRF Token</button>
    </div>

    <div id="output"></div>

    <script>
        function log(message, type = 'info') {
            const output = document.getElementById('output');
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? '#dc3545' : type === 'success' ? '#28a745' : '#007bff';
            output.innerHTML += `<div style="color: ${color}; margin: 5px 0;">[${timestamp}] ${message}</div>`;
            console.log(message);
        }

        function checkCSRF() {
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                log(`✅ CSRF Meta Token: ${metaToken.content.substring(0, 20)}...`, 'success');
            } else {
                log('❌ CSRF Meta Token not found', 'error');
            }

            const formToken = document.querySelector('input[name="_token"]');
            if (formToken) {
                log(`✅ Form Token: ${formToken.value.substring(0, 20)}...`, 'success');
            } else {
                log('❌ Form Token not found', 'error');
            }
        }

        function testAjaxLogout() {
            log('🔄 Testing AJAX logout...', 'info');
            
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                log(`Response Status: ${response.status}`, response.ok ? 'success' : 'error');
                return response.text();
            })
            .then(data => {
                log(`Response: ${data.substring(0, 200)}${data.length > 200 ? '...' : ''}`, 'info');
                if (data.includes('Redirecting') || data.includes('302')) {
                    log('✅ Logout appears successful (redirect detected)', 'success');
                }
            })
            .catch(error => {
                log(`❌ AJAX Error: ${error.message}`, 'error');
            });
        }

        // Auto-check CSRF on page load
        document.addEventListener('DOMContentLoaded', function() {
            log('🔄 Page loaded, checking CSRF...', 'info');
            checkCSRF();
        });
    </script>
</body>
</html>
