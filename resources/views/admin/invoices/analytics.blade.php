@extends('layouts.admin')

@section('title', 'Invoice Analytics')

@section('styles')
<style>
    .analytics-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px;
    }
    
    .analytics-header {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }
    
    .header-content {
        display: flex;
        justify-content: between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .header-info h1 {
        margin: 0 0 10px 0;
        color: #333;
        font-size: 28px;
        font-weight: 700;
    }
    
    .header-subtitle {
        color: #666;
        font-size: 16px;
        margin: 0;
    }
    
    .date-filters {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        background: white;
    }
    
    .filter-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-top: 20px;
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 35px rgba(0,0,0,0.15);
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-gradient);
    }
    
    .stat-card.revenue::before {
        --card-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card.invoices::before {
        --card-gradient: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }
    
    .stat-card.pending::before {
        --card-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card.growth::before {
        --card-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        background: var(--card-gradient);
    }
    
    .stat-trend {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 20px;
    }
    
    .trend-up {
        background: #e8f5e8;
        color: #28a745;
    }
    
    .trend-down {
        background: #ffeaea;
        color: #dc3545;
    }
    
    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
    
    .stat-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }
    
    .stat-subtitle {
        font-size: 12px;
        color: #999;
        margin-top: 5px;
    }
    
    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .chart-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }
    
    .chart-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .chart-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .chart-subtitle {
        font-size: 14px;
        color: #666;
        margin-top: 5px;
    }
    
    .chart-controls {
        display: flex;
        gap: 10px;
    }
    
    .chart-btn {
        padding: 6px 12px;
        border: 1px solid #ddd;
        background: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
    }
    
    .chart-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    .chart-canvas {
        height: 400px;
        position: relative;
    }
    
    .chart-placeholder {
        height: 400px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #666;
        font-size: 16px;
        font-weight: 500;
    }
    
    .recent-invoices {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    }
    
    .invoice-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .invoice-item:last-child {
        border-bottom: none;
    }
    
    .invoice-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .invoice-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }
    
    .invoice-details h6 {
        margin: 0 0 4px 0;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }
    
    .invoice-details small {
        color: #666;
        font-size: 12px;
    }
    
    .invoice-amount {
        text-align: right;
    }
    
    .amount-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }
    
    .invoice-status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-paid {
        background: #e8f5e8;
        color: #28a745;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-failed {
        background: #ffeaea;
        color: #dc3545;
    }
    
    .top-customers {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }
    
    .customer-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .customer-item:last-child {
        border-bottom: none;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .customer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 12px;
    }
    
    .customer-details h6 {
        margin: 0 0 3px 0;
        font-size: 13px;
        font-weight: 600;
        color: #333;
    }
    
    .customer-details small {
        color: #666;
        font-size: 11px;
    }
    
    .customer-stats {
        text-align: right;
        font-size: 12px;
    }
    
    .customer-amount {
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
    }
    
    .customer-count {
        color: #666;
    }
    
    .export-section {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .export-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }
    
    .export-description {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    .export-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .export-btn {
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    
    .btn-excel {
        background: linear-gradient(135deg, #1d6f42 0%, #2d8f5f 100%);
        color: white;
    }
    
    .btn-pdf {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }
    
    .btn-csv {
        background: linear-gradient(135deg, #fd7e14 0%, #e8690b 100%);
        color: white;
    }
    
    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        text-decoration: none;
        color: white;
    }
    
    @media (max-width: 768px) {
        .analytics-container {
            padding: 15px;
        }
        
        .header-content {
            flex-direction: column;
            align-items: stretch;
        }
        
        .date-filters {
            flex-direction: column;
            gap: 10px;
        }
        
        .charts-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        
        .export-buttons {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endsection

@section('content')
<div class="analytics-container">
    <!-- Header -->
    <div class="analytics-header">
        <div class="header-content">
            <div class="header-info">
                <h1>📊 Invoice Analytics Dashboard</h1>
                <p class="header-subtitle">Comprehensive insights into your invoice performance and revenue trends</p>
            </div>
            <div class="date-filters">
                <div class="filter-group">
                    <label class="filter-label">From Date</label>
                    <input type="date" class="filter-input" id="startDate" value="{{ date('Y-m-01') }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">To Date</label>
                    <input type="date" class="filter-input" id="endDate" value="{{ date('Y-m-d') }}">
                </div>
                <button class="filter-btn" onclick="updateAnalytics()">Update Data</button>
            </div>
        </div>
    </div>
    
    <!-- Key Statistics -->
    <div class="stats-grid">
        <div class="stat-card revenue">
            <div class="stat-header">
                <div class="stat-icon">💰</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i> +12.5%
                </div>
            </div>
            <div class="stat-value">$24,850</div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-subtitle">From 148 invoices this month</div>
        </div>
        
        <div class="stat-card invoices">
            <div class="stat-header">
                <div class="stat-icon">📄</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i> +8.3%
                </div>
            </div>
            <div class="stat-value">148</div>
            <div class="stat-label">Invoices Generated</div>
            <div class="stat-subtitle">92% automatically processed</div>
        </div>
        
        <div class="stat-card pending">
            <div class="stat-header">
                <div class="stat-icon">⏳</div>
                <div class="stat-trend trend-down">
                    <i class="fas fa-arrow-down"></i> -5.2%
                </div>
            </div>
            <div class="stat-value">12</div>
            <div class="stat-label">Pending Payments</div>
            <div class="stat-subtitle">$3,240 outstanding amount</div>
        </div>
        
        <div class="stat-card growth">
            <div class="stat-header">
                <div class="stat-icon">📈</div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i> +15.7%
                </div>
            </div>
            <div class="stat-value">94.2%</div>
            <div class="stat-label">Collection Rate</div>
            <div class="stat-subtitle">Average 3.2 days to payment</div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Revenue Trends</h3>
                    <p class="chart-subtitle">Monthly revenue and invoice volume over time</p>
                </div>
                <div class="chart-controls">
                    <button class="chart-btn active">6M</button>
                    <button class="chart-btn">1Y</button>
                    <button class="chart-btn">2Y</button>
                </div>
            </div>
            <div class="chart-canvas">
                <div class="chart-placeholder">
                    📊 Revenue chart will be rendered here using Chart.js
                </div>
            </div>
        </div>
        
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h3 class="chart-title">Payment Status</h3>
                    <p class="chart-subtitle">Current invoice payment distribution</p>
                </div>
            </div>
            <div class="chart-canvas">
                <div class="chart-placeholder">
                    🥧 Pie chart showing payment status distribution
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity and Top Customers -->
    <div class="row">
        <div class="col-lg-8">
            <div class="recent-invoices">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Recent Invoices</h3>
                        <p class="chart-subtitle">Latest invoice activity and status updates</p>
                    </div>
                    <a href="{{ route('admin.invoices.index') }}" class="chart-btn">View All</a>
                </div>
                
                <div class="invoice-item">
                    <div class="invoice-info">
                        <div class="invoice-avatar">JD</div>
                        <div class="invoice-details">
                            <h6>Invoice #INV-2024-001</h6>
                            <small>John Doe • 2 hours ago</small>
                        </div>
                    </div>
                    <div class="invoice-amount">
                        <div class="amount-value">$1,250.00</div>
                        <span class="invoice-status status-paid">Paid</span>
                    </div>
                </div>
                
                <div class="invoice-item">
                    <div class="invoice-info">
                        <div class="invoice-avatar">SM</div>
                        <div class="invoice-details">
                            <h6>Invoice #INV-2024-002</h6>
                            <small>Sarah Miller • 4 hours ago</small>
                        </div>
                    </div>
                    <div class="invoice-amount">
                        <div class="amount-value">$875.50</div>
                        <span class="invoice-status status-pending">Pending</span>
                    </div>
                </div>
                
                <div class="invoice-item">
                    <div class="invoice-info">
                        <div class="invoice-avatar">RJ</div>
                        <div class="invoice-details">
                            <h6>Invoice #INV-2024-003</h6>
                            <small>Robert Johnson • 6 hours ago</small>
                        </div>
                    </div>
                    <div class="invoice-amount">
                        <div class="amount-value">$2,100.75</div>
                        <span class="invoice-status status-paid">Paid</span>
                    </div>
                </div>
                
                <div class="invoice-item">
                    <div class="invoice-info">
                        <div class="invoice-avatar">EM</div>
                        <div class="invoice-details">
                            <h6>Invoice #INV-2024-004</h6>
                            <small>Emily Wilson • 1 day ago</small>
                        </div>
                    </div>
                    <div class="invoice-amount">
                        <div class="amount-value">$650.25</div>
                        <span class="invoice-status status-failed">Failed</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="top-customers">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Top Customers</h3>
                        <p class="chart-subtitle">Highest revenue contributors</p>
                    </div>
                </div>
                
                <div class="customer-item">
                    <div class="customer-info">
                        <div class="customer-avatar">AC</div>
                        <div class="customer-details">
                            <h6>Acme Corporation</h6>
                            <small>Premium Customer</small>
                        </div>
                    </div>
                    <div class="customer-stats">
                        <div class="customer-amount">$8,450</div>
                        <div class="customer-count">15 invoices</div>
                    </div>
                </div>
                
                <div class="customer-item">
                    <div class="customer-info">
                        <div class="customer-avatar">TI</div>
                        <div class="customer-details">
                            <h6>Tech Innovations Ltd</h6>
                            <small>Enterprise</small>
                        </div>
                    </div>
                    <div class="customer-stats">
                        <div class="customer-amount">$6,750</div>
                        <div class="customer-count">12 invoices</div>
                    </div>
                </div>
                
                <div class="customer-item">
                    <div class="customer-info">
                        <div class="customer-avatar">GS</div>
                        <div class="customer-details">
                            <h6>Global Solutions Inc</h6>
                            <small>Standard</small>
                        </div>
                    </div>
                    <div class="customer-stats">
                        <div class="customer-amount">$4,320</div>
                        <div class="customer-count">8 invoices</div>
                    </div>
                </div>
            </div>
            
            <div class="export-section">
                <h3 class="export-title">📥 Export Analytics</h3>
                <p class="export-description">
                    Download detailed analytics reports in your preferred format for further analysis and record keeping.
                </p>
                <div class="export-buttons">
                    <a href="#" class="export-btn btn-excel" onclick="exportData('excel')">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="#" class="export-btn btn-pdf" onclick="exportData('pdf')">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="#" class="export-btn btn-csv" onclick="exportData('csv')">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize the analytics dashboard
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        setupEventListeners();
    });
    
    function initializeCharts() {
        // This would initialize Chart.js charts with real data
        console.log('Initializing analytics charts...');
        
        // Simulate chart initialization
        setTimeout(() => {
            updateChartPlaceholders();
        }, 1000);
    }
    
    function updateChartPlaceholders() {
        const placeholders = document.querySelectorAll('.chart-placeholder');
        placeholders.forEach((placeholder, index) => {
            if (index === 0) {
                placeholder.innerHTML = `
                    <div style="display: flex; align-items: end; justify-content: space-around; height: 100%; padding: 20px;">
                        <div style="width: 30px; height: 60%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;"></div>
                        <div style="width: 30px; height: 80%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;"></div>
                        <div style="width: 30px; height: 45%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;"></div>
                        <div style="width: 30px; height: 90%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;"></div>
                        <div style="width: 30px; height: 70%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;"></div>
                        <div style="width: 30px; height: 55%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 4px 4px 0 0;"></div>
                    </div>
                    <div style="text-align: center; color: #666; font-size: 14px; margin-top: 15px;">📊 Revenue Trend Chart</div>
                `;
            } else {
                placeholder.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%;">
                        <div style="width: 200px; height: 200px; border-radius: 50%; background: conic-gradient(#667eea 0deg 180deg, #56ab2f 180deg 270deg, #f093fb 270deg 360deg); display: flex; align-items: center; justify-content: center;">
                            <div style="width: 100px; height: 100px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; font-weight: 600;">
                                Payment<br>Status
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; color: #666; font-size: 14px; margin-top: 15px;">🥧 Payment Status Distribution</div>
                `;
            }
        });
    }
    
    function setupEventListeners() {
        // Chart period buttons
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.classList.contains('chart-btn')) return;
                
                const parent = this.closest('.chart-controls');
                if (parent) {
                    parent.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update chart based on selected period
                    updateChartData(this.textContent);
                }
            });
        });
    }
    
    function updateAnalytics() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            Swal.fire({
                icon: 'warning',
                title: 'Date Range Required',
                text: 'Please select both start and end dates to update analytics.',
            });
            return;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Date Range',
                text: 'Start date cannot be later than end date.',
            });
            return;
        }
        
        // Show loading
        Swal.fire({
            title: 'Updating Analytics...',
            text: 'Please wait while we fetch the latest data.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate API call
        setTimeout(() => {
            // Update statistics with animation
            animateStatistics();
            
            // Update charts
            updateChartPlaceholders();
            
            Swal.close();
            
            Swal.fire({
                icon: 'success',
                title: 'Analytics Updated!',
                text: `Data refreshed for ${startDate} to ${endDate}`,
                timer: 2000,
                showConfirmButton: false
            });
        }, 2000);
    }
    
    function animateStatistics() {
        const statValues = document.querySelectorAll('.stat-value');
        
        statValues.forEach((stat, index) => {
            const currentValue = stat.textContent;
            const isNumber = !isNaN(parseFloat(currentValue.replace(/[^0-9.]/g, '')));
            
            if (isNumber) {
                // Animate number changes
                stat.style.transform = 'scale(1.1)';
                stat.style.color = '#667eea';
                
                setTimeout(() => {
                    stat.style.transform = 'scale(1)';
                    stat.style.color = '#333';
                }, 300);
            }
        });
    }
    
    function updateChartData(period) {
        console.log(`Updating chart data for period: ${period}`);
        
        // Simulate chart update
        const chartPlaceholders = document.querySelectorAll('.chart-placeholder');
        chartPlaceholders.forEach(placeholder => {
            placeholder.style.opacity = '0.5';
            setTimeout(() => {
                placeholder.style.opacity = '1';
            }, 500);
        });
    }
    
    function exportData(format) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        Swal.fire({
            title: `Export ${format.toUpperCase()} Report`,
            text: `Preparing analytics report from ${startDate} to ${endDate}...`,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Simulate export process
        setTimeout(() => {
            Swal.close();
            
            // In a real implementation, this would trigger a download
            const fileName = `invoice-analytics-${startDate}-to-${endDate}.${format}`;
            
            Swal.fire({
                icon: 'success',
                title: 'Export Complete!',
                text: `Your ${format.toUpperCase()} report has been generated successfully.`,
                confirmButtonText: 'Download',
                showCancelButton: true,
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trigger download
                    console.log(`Downloading: ${fileName}`);
                    
                    // Create a temporary download link
                    const link = document.createElement('a');
                    link.href = `data:text/plain;charset=utf-8,Sample ${format.toUpperCase()} export data for invoice analytics`;
                    link.download = fileName;
                    link.click();
                }
            });
        }, 2000);
    }
    
    // Real-time updates (optional)
    function startRealTimeUpdates() {
        setInterval(() => {
            // Update real-time statistics
            updateRealTimeStats();
        }, 30000); // Update every 30 seconds
    }
    
    function updateRealTimeStats() {
        // This would fetch real-time data from the server
        console.log('Updating real-time statistics...');
    }
    
    // Initialize real-time updates
    // startRealTimeUpdates();
</script>
@endsection
