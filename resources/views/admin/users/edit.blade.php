<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[#FFA500] leading-tight">
                {{ __('Edit User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-[#2A2A2A] text-[#E0E0E0] rounded-lg hover:bg-[#333333] transition-colors duration-200 border border-[#333333] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#1F1F1F] overflow-hidden shadow-sm sm:rounded-lg border border-[#333333]">
                <div class="p-6">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 needs-confirm">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#E0E0E0] mb-2 flex items-center">
                                {{ __('Name') }}
                                <span class="text-red-400 ml-1">*</span>
                            </label>
                            <input id="name" 
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" 
                                type="text" 
                                name="name" 
                                value="{{ old('name', $user->name) }}"
                                placeholder="Enter user name"
                                required 
                                autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-6">
                            <label for="email" class="block text-sm font-semibold text-[#E0E0E0] mb-2 flex items-center">
                                {{ __('Email') }}
                                <span class="text-red-400 ml-1">*</span>
                            </label>
                            <input id="email" 
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}"
                                placeholder="Enter email address"
                                required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-6 relative">
                            <label for="password" class="block text-sm font-semibold text-[#E0E0E0] mb-2">
                                {{ __('New Password') }}
                            </label>
                            <input id="password" 
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm"
                                type="password"
                                name="password"
                                placeholder="Enter new password"
                                onfocus="showPasswordRequirements()" 
                                onblur="hidePasswordRequirements()"
                                oninput="checkPasswordRequirements()" />
                            <p class="mt-1 text-sm text-[#E0E0E0]">Leave empty to keep current password</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <div id="password-requirements" class="hidden absolute left-0 top-full mt-2 w-full z-20 bg-[#2A2A2A] border border-[#333333] rounded-lg p-4 text-sm text-[#E0E0E0] shadow-lg">
                                <div class="mb-2 font-semibold text-[#FFA500]">Password harus mengandung:</div>
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
                            <label for="password_confirmation" class="block text-sm font-semibold text-[#E0E0E0] mb-2">
                                {{ __('Confirm New Password') }}
                            </label>
                            <input id="password_confirmation" 
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm new password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mt-6">
                            <label for="role" class="block text-sm font-semibold text-[#E0E0E0] mb-2 flex items-center">
                                {{ __('Role') }}
                                <span class="text-red-400 ml-1">*</span>
                            </label>
                            <select id="role" 
                                name="role" 
                                required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm">
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="receptionist" {{ $user->role === 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-6">
                            <label for="phone" class="block text-sm font-semibold text-[#E0E0E0] mb-2 flex items-center">
                                {{ __('Phone Number') }}
                                <span class="text-red-400 ml-1">*</span>
                            </label>
                            <input id="phone" 
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="Enter phone number"
                                required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label for="address" class="block text-sm font-semibold text-[#E0E0E0] mb-2 flex items-center">
                                {{ __('Address') }}
                                <span class="text-red-400 ml-1">*</span>
                            </label>
                            <textarea id="address" 
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" 
                                name="address" 
                                placeholder="Enter address"
                                required 
                                rows="3">{{ old('address', $user->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-4 border-t border-[#333333]">
                            <button type="button" 
                                onclick="window.location.href='{{ route('admin.users.index') }}'" 
                                class="px-4 py-2 bg-[#2A2A2A] text-[#E0E0E0] rounded-lg hover:bg-[#333333] transition-colors duration-200 border border-[#333333] mr-3">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-[#FFA500] text-white rounded-lg hover:bg-[#ff8c1a] transition-colors duration-200">
                                {{ __('Update User') }}
                            </button>
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