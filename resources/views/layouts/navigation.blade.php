<!-- resources/views/layouts/navigation.blade.php -->

<nav class="bg-white shadow-md flex items-center justify-between p-4">
    <div class="flex items-center space-x-4">
        <a href="{{ route('dashboard') }}" class="text-gray-700 font-bold text-lg">Dashboard</a>
    </div>
    <div class="flex items-center space-x-4">
        <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-indigo-600">Profile</a>
        <!-- <a href="#" class="text-gray-600 hover:text-indigo-600">Settings</a> -->
        
        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="text-gray-600 hover:text-indigo-600">Logout</button>
        </form>
    </div>
</nav>
