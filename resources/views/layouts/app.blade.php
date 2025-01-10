<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        @include('layouts.navigation') <!-- Navigation Bar -->

        <div class="flex flex-1">
            <!-- Sidebar Layout -->
            <aside class="w-64 h-screen bg-gradient-to-b from-gray-800 to-gray-900 text-white shadow-lg flex-shrink-0">
                <div class="p-4">
                    <!-- <h2 class="text-2xl font-bold text-gray-200 mb-6">Dashboard</h2> -->
                    <ul class="space-y-4">
    <li>
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
            Dashboard
        </a>
    </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out" onclick="toggleVehicleTaxSubmenu()">
                                Vehicle Tax
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <ul id="vehicle-tax-submenu" class="space-y-4 pl-8" style="display: none;">
                                <li>
                                    <a href="{{ route('vehicle-tax.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Vehicle Tax
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vehicle-tax.logs') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Vehicle Tax Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out" onclick="toggleVehiclePermitSubmenu()">
                                Vehicle Permit
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <ul id="vehicle-permit-submenu" class="space-y-4 pl-8" style="display: none;">
                                <li>
                                    <a href="{{ route('vehicle-permit.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Vehicle Permit
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vehicle-permit-logs.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Vehicle Permit Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out" onclick="toggleEnvironmentTaxSubmenu()">
                                Environment Tax
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <ul id="environment-tax-submenu" class="space-y-4 pl-8" style="display: none;">
                                <li>
                                    <a href="{{ route('environment-tax.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Environment Tax
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('environment-tax-logs.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Environment Tax Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out" onclick="toggleVehicleFitnessSubmenu()">
                                Vehicle Fitness
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <ul id="vehicle-fitness-submenu" class="space-y-4 pl-8" style="display: none;">
                                <li>
                                    <a href="{{ route('vehicle-fitness.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Vehicle Fitness
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vehicle-fitness.logs') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Vehicle Fitness Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out" onclick="toggleEchallanSubmenu()">
                                E-challan
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <ul id="echallan-submenu" class="space-y-4 pl-8" style="display: none;">
                                <li>
                                    <a href="{{ route('echallan.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        E-challan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('echallan-logs.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        E-challan Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out" onclick="toggleDrivingLicenseSubmenu()">
                                Driving License
                                <svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <ul id="driving-license-submenu" class="space-y-4 pl-8" style="display: none;">
                                <li>
                                    <a href="{{ route('driving-licenses.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Driving License
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('driving-license-logs.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                        Driving License Logs
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                      
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-10 bg-white shadow-md rounded-lg overflow-auto">
                @if(isset($slot))  <!-- Check if slot is defined -->
                    {{ $slot }}    <!-- Use slot if it's a component -->
                @else
                    @yield('content')  <!-- Otherwise yield content for section-based views -->
                @endif
            </main>
        </div>
    </div>
    <script>
        function toggleVehicleTaxSubmenu() {
            var submenu = document.getElementById('vehicle-tax-submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
        function toggleVehiclePermitSubmenu() {
            var submenu = document.getElementById('vehicle-permit-submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
        function toggleEchallanSubmenu() {
            var submenu = document.getElementById('echallan-submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
        function toggleVehicleFitnessSubmenu() {
            var submenu = document.getElementById('vehicle-fitness-submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
        
        function toggleEnvironmentTaxSubmenu() {
            var submenu = document.getElementById('environment-tax-submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
        function toggleDrivingLicenseSubmenu() {
            var submenu = document.getElementById('driving-license-submenu');
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
            } else {
                submenu.style.display = 'block';
            }
        }
    </script>
