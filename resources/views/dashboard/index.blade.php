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
                <h2 class="text-base font-bold text-gray-800">Total Students</h2>
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

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Announcements</h2>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\Announcement::count() }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-shadow transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-center">
                <h2 class="text-base font-bold text-gray-800">Messages</h2>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-1.5">{{ \App\Models\Message::where('status', 'unread')->count() }}</p>
        </div>

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
<div class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-base font-bold text-gray-800">Monthly Reports for Medical & Dental Appointments</h2>
        <div class="relative">
            <select id="timeFilter" class="block appearance-none bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-xs">
                <option value="12">Last 12 months</option>
                <option value="6">Last 6 months</option>
                <option value="3">Last 3 months</option>
            </select>
        </div>
    </div>
    <div class="h-64">
        <canvas id="appointmentsChart"></canvas>
    </div>
</div>

<div class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-base font-bold text-gray-800">Monthly Reports for App Usage of UMAK Students</h2>
        <div class="relative">
            <select id="timeFilter" class="block appearance-none bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-xs">
                <option value="12" selected>Last 12 months</option>
                <option value="6">Last 6 months</option>
                <option value="3">Last 3 months</option>
            </select>
        </div>
    </div>
    <div class="h-64">
        <canvas id="registrationsChart"></canvas>
    </div>
</div>

<div class="bg-white p-4 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-base font-bold text-gray-800">Monthly Feedback Reports</h2>
        <div class="relative">
            <select id="timeFilter" class="block appearance-none bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 text-xs">
                <option value="12" selected>Last 12 months</option>
                <option value="6">Last 6 months</option>
                <option value="3">Last 3 months</option>
            </select>
        </div>
    </div>
    <div class="h-64">
        <canvas id="feedbackChart"></canvas>
    </div>
</div>



</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let appointmentsChart;

        function fetchChartData(months = 12) {
            $.ajax({
                url: `/appointments-data?months=${months}`,
                type: 'GET',
                success: function(response) {
                    updateChart(response.labels, response.medicalData, response.dentalData);
                }
            });
        }

        function updateChart(labels, medicalData, dentalData) {
            const ctx = document.getElementById('appointmentsChart').getContext('2d');

            if (appointmentsChart) {
                appointmentsChart.destroy();
            }

            appointmentsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Medical Appointments',
                            data: medicalData,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Dental Appointments',
                            data: dentalData,
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
                            stacked: true
                        }
                    }
                }
            });
        }

        $('#timeFilter').change(function() {
            let months = $(this).val();
            fetchChartData(months);
        });

        // Load default chart
        fetchChartData();
    });



document.addEventListener("DOMContentLoaded", function () {
    let registrationsChart;

    function fetchRegistrationsChart(months = 12) {
        $.ajax({
            url: `/registrations-data?months=${months}`,
            type: 'GET',
            success: function (response) {
                console.log("Fetched Data:", response); // Debugging
                if (response.labels.length === 0 || response.data.length === 0) {
                    console.warn("No data returned for the selected period.");
                }
                updateRegistrationsChart(response.labels, response.data);
            },
            error: function (xhr, status, error) {
                console.error("Failed to fetch registrations data:", status, error);
            }
        });
    }

    function updateRegistrationsChart(labels, data) {
        const ctx = document.getElementById('registrationsChart').getContext('2d');

        if (registrationsChart) {
            registrationsChart.destroy();
        }

        registrationsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Active User Registrations',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointRadius: 4,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month & Year'
                        },
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 6,
                            maxRotation: 0,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Active Registrations'
                        }
                    }
                }
            }
        });
    }

    $('#timeFilter').change(function () {
        let months = $(this).val();
        fetchRegistrationsChart(months);
    });

    // Load default chart
    fetchRegistrationsChart();
});

document.addEventListener('DOMContentLoaded', function () {
    function fetchFeedbackChart(months = 12) {
        $.ajax({
            url: "/feedback/chart-data",
            type: "GET",
            data: { months: months },
            success: function (data) {
                let labels = data.map(item => item.month);
                let ratings = [1, 2, 3, 4, 5]; // Star ratings
                let ratingCounts = ratings.map(rating => data.map(item => item.ratings[rating] || 0));

                let ctx = document.getElementById("feedbackChart").getContext("2d");

                // Destroy previous chart instance if it exists
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
                            borderWidth: 1
                        }))
                    },
                 options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                align: 'center', // Centers the legend
                                labels: {
                                    boxWidth: 15,
                                    padding: 10
                                }
                            }
                        },
                        scales: {
                            y: { beginAtZero: true, stacked: true },
                            x: {
                                stacked: true,
                                ticks: {
                                    callback: function(value, index) {
                                        let date = new Date(labels[index] + "-01");
                                        return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    }

    fetchFeedbackChart();

    document.getElementById("timeFilter").addEventListener("change", function () {
        fetchFeedbackChart(this.value);
    });
});





</script>
@endsection