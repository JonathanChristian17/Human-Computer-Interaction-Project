<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[#FFA040] leading-tight">
                {{ __('Add New User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-[#1D1D1D] text-white rounded-lg hover:bg-[#2D2D2D] transition-colors duration-200 border border-[#FFA040] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#252525] overflow-hidden shadow-sm sm:rounded-lg border border-[#FFA040]">
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 needs-confirm">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Name') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="name" 
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}"
                                placeholder="Enter user name"
                                required 
                                autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-6">
                            <label for="email" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Email') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="email" 
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="Enter email address"
                                required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-6 relative">
                            <label for="password" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Password') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="password" 
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm"
                                type="password"
                                name="password"
                                placeholder="Enter password"
                                required 
                                onfocus="showPasswordRequirements()" 
                                onblur="hidePasswordRequirements()"
                                oninput="checkPasswordRequirements()" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <div id="password-requirements"
                                 class="hidden absolute left-0 top-full mt-2 w-full z-20 bg-[#232323] border border-[#FFA040] rounded-lg p-4 text-sm text-white shadow-lg">
                                <div class="mb-2 font-semibold text-[#FFA040]">Password harus mengandung:</div>
                                <ul>
                                    <li id="pw-length" class="flex items-center mb-1"><span class="mr-2">&#10060;</span> Minimal 8 karakter</li>
                                    <li id="pw-uppercase" class="flex items-center mb-1"><span class="mr-2">&#10060;</span> Huruf kapital</li>
                                    <li id="pw-lowercase" class="flex items-center mb-1"><span class="mr-2">&#10060;</span> Huruf kecil</li>
                                    <li id="pw-number" class="flex items-center mb-1"><span class="mr-2">&#10060;</span> Angka</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-6">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Confirm Password') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="password_confirmation" 
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm password"
                                required />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-6">
                            <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Phone') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="phone" 
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm"
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="Enter phone number"
                                required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label for="address" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Address') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="address" 
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm"
                                type="text"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Enter address"
                                required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mt-6">
                            <label for="role" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Role') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select id="role" 
                                name="role" 
                                required
                                class="w-full px-5 py-3 bg-[#1D1D1D] border border-[#FFA040] rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#FFA040] focus:border-transparent transition shadow-sm">
                                <option value="">Select role</option>
                                <option value="customer">Customer</option>
                                <option value="receptionist">Receptionist</option>
                                <option value="admin">Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-4 border-t border-[#FFA040]">
                            <x-secondary-button type="button" onclick="window.location.href='{{ route('admin.users.index') }}'" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

<script>
function showPasswordRequirements() {
    document.getElementById('password-requirements').classList.remove('hidden');
}
function hidePasswordRequirements() {
    setTimeout(() => {
        document.getElementById('password-requirements').classList.add('hidden');
    }, 200);
}
function checkPasswordRequirements() {
    const pw = document.getElementById('password').value;
    document.getElementById('pw-length').innerHTML = (pw.length >= 8 ? '<span class="mr-2 text-green-400">&#10003;</span>' : '<span class="mr-2">&#10060;</span>') + ' Minimal 8 karakter';
    document.getElementById('pw-uppercase').innerHTML = (/[A-Z]/.test(pw) ? '<span class="mr-2 text-green-400">&#10003;</span>' : '<span class="mr-2">&#10060;</span>') + ' Huruf kapital';
    document.getElementById('pw-lowercase').innerHTML = (/[a-z]/.test(pw) ? '<span class="mr-2 text-green-400">&#10003;</span>' : '<span class="mr-2">&#10060;</span>') + ' Huruf kecil';
    document.getElementById('pw-number').innerHTML = (/[0-9]/.test(pw) ? '<span class="mr-2 text-green-400">&#10003;</span>' : '<span class="mr-2">&#10060;</span>') + ' Angka';
}
</script> 