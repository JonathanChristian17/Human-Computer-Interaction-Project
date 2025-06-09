<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Edit User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Name') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="name" 
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm" 
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
                            <label for="email" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Email') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="email" 
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}"
                                placeholder="Enter email address"
                                required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-6">
                            <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">
                                {{ __('New Password') }}
                            </label>
                            <input id="password" 
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm"
                                type="password"
                                name="password"
                                placeholder="Enter new password" />
                            <p class="mt-1 text-sm text-gray-400">Leave empty to keep current password</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-6">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-300 mb-2">
                                {{ __('Confirm New Password') }}
                            </label>
                            <input id="password_confirmation" 
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm new password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm">
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                                <option value="receptionist" {{ $user->role === 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-6">
                            <label for="phone" class="block text-sm font-semibold text-gray-300 mb-2 flex items-center">
                                {{ __('Phone Number') }}
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input id="phone" 
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm" 
                                type="text" 
                                name="phone" 
                                value="{{ old('phone', $user->phone) }}"
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
                            <textarea id="address" 
                                class="w-full px-5 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-sm" 
                                name="address" 
                                placeholder="Enter address"
                                required 
                                rows="3">{{ old('address', $user->address) }}</textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-4 border-t border-gray-600">
                            <x-secondary-button type="button" onclick="window.location.href='{{ route('admin.users.index') }}'" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Update User') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout> 