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
                            <a href="{{ route('vehicle-tax.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                Vehicle Tax
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vehicle-permit.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                Vehicle Permit
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('environment-tax.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                Environment Tax
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('vehicle-fitness.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                Vehicle Fitness 
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('echallan.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                E-challan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('driver-license.index') }}" class="flex items-center px-4 py-2 text-lg font-semibold bg-gray-700 hover:bg-indigo-600 rounded-lg transition-colors duration-300 ease-in-out">
                                Driving License
                            </a>
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
</body>
</html>
