<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            
        </h2>
    </x-slot>

    <div class="py-12 w-7/10 h-screen bg-cover bg-center" style="background-image: url('/RTOLight.jpg'); margin: 0 auto;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div style="width: 25%; float: left;">{{ __("You're logged in!") }}</div>
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md" style="width: 25%; float: right; text-align: center;">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Message Balance</h2>
                            <p class="text-2xl font-bold text-green-500">{{ number_format($balance) }}</p>
                        </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
