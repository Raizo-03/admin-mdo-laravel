@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-5">
    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Dashboard</h1>
        <p class="text-gray-300 text-sm">All reports and statistics are displayed in this dashboard</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-5">
        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Users</h2>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\User::count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Students</h2>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\User::where('role', 'student')->count() }}</p>
        </div>

                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Faculty</h2>
                <div class="p-2 bg-purple-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\User::where('role', 'faculty')->count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Admins</h2>
                <div class="p-2 bg-red-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5"> {{ \App\Models\Admin::where('role', 'admin')->count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Appointments</h2>
                <div class="p-2 bg-yellow-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\Appointment::count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Feedbacks</h2>
                <div class="p-2 bg-purple-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\Feedback::count() }}</p>
        </div>

        {{-- <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Announcements</h2>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\Announcement::count() }}</p>
        </div> --}}

        {{-- <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Messages</h2>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\Message::where('status', 'unread')->count() }}</p>
        </div> --}}

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Doctors</h2>
                <div class="p-2 bg-indigo-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5"> {{ \App\Models\Admin::where('role', 'doctor')->count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Total Nurses</h2>
                <div class="p-2 bg-pink-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5"> {{ \App\Models\Admin::where('role', 'nurse')->count() }}</p>
        </div>
    </div>

   <!-- Add download buttons to each chart section -->

<!-- User Distribution Chart Section -->
<div class="bg-white p-4 rounded-lg shadow-md mb-5">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-base font-bold text-gray-800">User Distribution by Type</h2>
        <!-- Add download button -->
        <button onclick="downloadChartContainer('userTypesChart', 'User_Distribution_Chart')" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
            <i class="fas fa-download mr-1"></i> Download PNG
        </button>
    </div>
    <div class="flex flex-col md:flex-row items-center">
        <div class="w-full md:w-1/2 h-64">
            <canvas id="userTypesChart"></canvas>
        </div>
        <div class="w-full md:w-1/2 mt-4 md:mt-0 md:ml-6">
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-gray-700 font-medium">Students</span>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-800">{{ \App\Models\User::where('role', 'student')->count() }}</div>
                        <div class="text-sm text-gray-500" id="studentPercentage">0%</div>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-500 rounded-full mr-3"></div>
                        <span class="text-gray-700 font-medium">Faculty</span>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-800">{{ \App\Models\User::where('role', 'faculty')->count() }}</div>
                        <div class="text-sm text-gray-500" id="facultyPercentage">0%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Appointments Chart Section -->
<div class="bg-white p-4 rounded-lg shadow-md mb-5">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-base font-bold text-gray-800">Monthly Reports for Medical & Dental Appointments</h2>
        <div class="flex items-center space-x-2">
            <div class="relative">
                <select id="timeFilter" class="block appearance-none bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-xs">
                    <option value="12">Last 12 months</option>
                    <option value="6">Last 6 months</option>
                    <option value="3">Last 3 months</option>
                </select>
            </div>
            <!-- Add download button -->
            <button onclick="downloadChart('appointmentsChart', 'Appointments_Chart')" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                <i class="fas fa-download mr-1"></i> Download PNG
            </button>
        </div>
    </div>
    <div class="h-64">
        <canvas id="appointmentsChart"></canvas>
    </div>
</div>

<!-- Monthly Feedback Chart Section -->
<div class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-base font-bold text-gray-800">Monthly Feedback Reports</h2>
        <div class="flex items-center space-x-2">
            <div class="relative">
                <select id="feedbackTimeFilter" class="block appearance-none bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-xs">
                    <option value="12" selected>Last 12 months</option>
                    <option value="6">Last 6 months</option>
                    <option value="3">Last 3 months</option>
                </select>
            </div>
            <!-- Add download button -->
            <button onclick="downloadChart('feedbackChart', 'Feedback_Chart')" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                <i class="fas fa-download mr-1"></i> Download PNG
            </button>
        </div>
    </div>
    <div class="h-64">
        <canvas id="feedbackChart"></canvas>
    </div>
</div>

<!-- Add Font Awesome for icons (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Add html2canvas library for capturing entire divs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<script>
// Global chart instances
let appointmentsChart;
let userTypesChart;

// Function to download chart as PNG
function downloadChart(canvasId, filename) {
    try {
        const canvas = document.getElementById(canvasId);
        if (!canvas) {
            alert('Chart not found. Please make sure the chart is loaded.');
            return;
        }

        // Create a temporary canvas with white background
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        
        // Set canvas dimensions
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;
        
        // Fill with white background
        tempCtx.fillStyle = 'white';
        tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
        
        // Draw the chart on top of white background
        tempCtx.drawImage(canvas, 0, 0);
        
        // Create download link
        const link = document.createElement('a');
        link.download = `${filename}_${new Date().toISOString().split('T')[0]}.png`;
        link.href = tempCanvas.toDataURL('image/png');
        
        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        console.log(`Chart downloaded: ${link.download}`);
    } catch (error) {
        console.error('Error downloading chart:', error);
        alert('Failed to download chart. Please try again.');
    }
}

// Function to download entire chart container as PNG (including legends and stats)
function downloadChartContainer(containerId, filename) {
    try {
        const container = document.querySelector(`#${containerId}`).closest('.bg-white');
        if (!container) {
            alert('Chart container not found. Please make sure the chart is loaded.');
            return;
        }

        // Use html2canvas library to capture the entire div
        html2canvas(container, {
            backgroundColor: '#ffffff',
            scale: 2, // Higher resolution
            useCORS: true,
            allowTaint: false,
            height: container.offsetHeight,
            width: container.offsetWidth,
            scrollX: 0,
            scrollY: 0
        }).then(canvas => {
            // Create download link
            const link = document.createElement('a');
            link.download = `${filename}_${new Date().toISOString().split('T')[0]}.png`;
            link.href = canvas.toDataURL('image/png');
            
            // Trigger download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            console.log(`Chart container downloaded: ${link.download}`);
        }).catch(error => {
            console.error('Error capturing chart container:', error);
            // Fallback to canvas-only download
            downloadChart(containerId, filename);
        });
    } catch (error) {
        console.error('Error downloading chart container:', error);
        // Fallback to canvas-only download
        downloadChart(containerId, filename);
    }
}

// Function to download all charts at once
function downloadAllCharts() {
    const charts = [
        { id: 'userTypesChart', name: 'User_Distribution_Chart', isContainer: true },
        { id: 'appointmentsChart', name: 'Appointments_Chart', isContainer: false },
        { id: 'feedbackChart', name: 'Feedback_Chart', isContainer: false }
    ];
    
    charts.forEach((chart, index) => {
        setTimeout(() => {
            if (chart.isContainer) {
                downloadChartContainer(chart.id, chart.name);
            } else {
                downloadChart(chart.id, chart.name);
            }
        }, index * 1000); // Delay each download by 1 second
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Appointments Chart Logic
    function fetchChartData(months = 12) {
        console.log('Fetching chart data for months:', months);
        
        $.ajax({
            url: `/appointments-data?months=${months}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                console.log('Sending AJAX request to:', `/appointments-data?months=${months}`);
            },
            success: function(response) {
                console.log('AJAX Success - Response received:', response);
                
                if (response && response.labels && response.medicalData && response.dentalData) {
                    console.log('Valid response structure, updating chart...');
                    updateChart(response.labels, response.medicalData, response.dentalData);
                } else {
                    console.error('Invalid response structure:', response);
                    showError('Invalid data format received from server');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                
                showError(`Failed to load chart data: ${error || 'Unknown error'}`);
            }
        });
    }

    function updateChart(labels, medicalData, dentalData) {
        console.log('Updating chart with:', { labels, medicalData, dentalData });
        
        const chartElement = document.getElementById('appointmentsChart');
        if (!chartElement) {
            console.error('Chart element not found!');
            return;
        }
        
        const ctx = chartElement.getContext('2d');

        if (appointmentsChart) {
            appointmentsChart.destroy();
        }

        if (!labels || labels.length === 0) {
            showError('No data available for the selected period');
            return;
        }

        appointmentsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Medical Appointments',
                        data: medicalData || [],
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Dental Appointments',
                        data: dentalData || [],
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                if (Number.isInteger(value)) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            }
        });
        
        console.log('Chart created successfully');
    }

    function showError(message) {
        const chartContainer = document.getElementById('appointmentsChart');
        if (chartContainer) {
            const errorDiv = document.createElement('div');
            errorDiv.innerHTML = `
                <div style="text-align: center; padding: 20px; color: #dc3545; border: 1px solid #dc3545; border-radius: 4px; margin: 10px 0;">
                    <p><strong>Error:</strong> ${message}</p>
                    <button onclick="fetchChartData()" style="padding: 8px 16px; margin-top: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                        Retry
                    </button>
                </div>
            `;
            chartContainer.parentNode.insertBefore(errorDiv, chartContainer);
        }
        console.error('Chart Error:', message);
    }

    // Check dependencies
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        showError('jQuery is not loaded');
        return;
    }

    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded!');
        showError('Chart.js is not loaded');
        return;
    }

    // Event listeners
    $('#timeFilter').change(function() {
        let months = $(this).val();
        console.log('Time filter changed to:', months);
        fetchChartData(months);
    });

    // Load default chart
    console.log('Loading default chart...');
    fetchChartData();
});

// Feedback Chart Logic
document.addEventListener('DOMContentLoaded', function () {
    function fetchFeedbackChart(months = 12) {
        $.ajax({
            url: "/feedback/chart-data",
            type: "GET",
            data: { months: months },
            success: function (data) {
                console.log('Chart data received:', data);
                
                let labels = data.map(item => item.month);
                let ratings = [1, 2, 3, 4, 5];
                let ratingCounts = ratings.map(rating => data.map(item => item.ratings[rating] || 0));

                let ctx = document.getElementById("feedbackChart").getContext("2d");

                if (window.feedbackChartInstance) {
                    window.feedbackChartInstance.destroy();
                }

                window.feedbackChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: ratings.map((rating, index) => ({
                            label: `${rating}-Star Ratings`,
                            data: ratingCounts[index],
                            backgroundColor: `rgba(${index * 50}, ${255 - index * 50}, 200, 0.6)`, 
                            borderColor: `rgba(${index * 50}, ${255 - index * 50}, 200, 1)`,
                            borderWidth: 1,
                            maxBarThickness: 60
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'center',
                                labels: {
                                    boxWidth: 15,
                                    padding: 10
                                }
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                stacked: true,
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    }
                                }
                            },
                            x: {
                                stacked: true,
                                categoryPercentage: 0.8,
                                barPercentage: 0.9,
                                ticks: {
                                    callback: function(value, index) {
                                        let date = new Date(labels[index] + "-01");
                                        return date.toLocaleString('en-US', { month: 'short', year: 'numeric' });
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching chart data:', error);
            }
        });
    }

    fetchFeedbackChart();

    // Fix the feedback time filter to use the correct ID
    $('#feedbackTimeFilter').change(function() {
        let months = $(this).val();
        console.log('Feedback time filter changed to:', months);
        fetchFeedbackChart(months);
    });
});

// User Types Chart Logic
document.addEventListener('DOMContentLoaded', function() {
    const userTypesCtx = document.getElementById('userTypesChart').getContext('2d');
    
    const studentsCount = {{ \App\Models\User::where('role', 'student')->count() }};
    const facultyCount = {{ \App\Models\User::where('role', 'faculty')->count() }};
    const totalUsers = studentsCount + facultyCount;
    
    const studentPercentage = totalUsers > 0 ? Math.round((studentsCount / totalUsers) * 100) : 0;
    const facultyPercentage = totalUsers > 0 ? Math.round((facultyCount / totalUsers) * 100) : 0;
    
    document.getElementById('studentPercentage').textContent = studentPercentage + '%';
    document.getElementById('facultyPercentage').textContent = facultyPercentage + '%';
    
    userTypesChart = new Chart(userTypesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Students', 'Faculty'],
            datasets: [{
                data: [studentsCount, facultyCount],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(147, 51, 234, 0.8)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(147, 51, 234, 1)'
                ],
                borderWidth: 2,
                hoverBackgroundColor: [
                    'rgba(34, 197, 94, 0.9)',
                    'rgba(147, 51, 234, 0.9)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const percentage = totalUsers > 0 ? Math.round((value / totalUsers) * 100) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                animateScale: true
            }
        }
    });
});
</script>

</div>
@endsection
@section('scripts')