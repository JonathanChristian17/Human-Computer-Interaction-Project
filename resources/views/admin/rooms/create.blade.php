<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-100 leading-tight">
                {{ __('Add New Room') }}
            </h2>
            <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                ← Back to Rooms
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Room Number -->
                        <div>
                            <x-input-label for="room_number" :value="__('Room Number')" class="text-gray-300" />
                            <x-text-input id="room_number" name="room_number" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-300" :value="old('room_number')" required autofocus />
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                        </div>

                        <!-- Room Name -->
                        <div>
                            <x-input-label for="name" :value="__('Room Name')" class="text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-300" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Room Type -->
                        <div>
                            <x-input-label for="type" :value="__('Room Type')" class="text-gray-300" />
                            <select id="type" name="type" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="standard">Standard</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="suite">Suite</option>
                                <option value="family">Family</option>
                                <option value="luxury">Luxury</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Price -->
                        <div>
                            <x-input-label for="price_per_night" :value="__('Price per Night (Rp)')" class="text-gray-300" />
                            <x-text-input id="price_per_night" name="price_per_night" type="text" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-300" :value="old('price_per_night')" required />
                            <x-input-error :messages="$errors->get('price_per_night')" class="mt-2" />
                        </div>

                        <!-- Capacity -->
                        <div>
                            <x-input-label for="capacity" :value="__('Capacity (Persons)')" class="text-gray-300" />
                            <x-text-input id="capacity" name="capacity" type="number" min="1" class="mt-1 block w-full bg-gray-700 border-gray-600 text-gray-300" :value="old('capacity')" required />
                            <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <x-input-label for="description" :value="__('Description')" class="text-gray-300" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Room Image -->
                        <div>
                            <x-input-label for="image" :value="__('Room Image')" class="text-gray-300" />
                            <div class="mt-2 mb-4">
                                <p class="text-sm font-medium text-gray-300 mb-2">Image Preview:</p>
                                <img id="preview" src="#" alt="Preview" class="w-48 h-32 object-cover rounded-lg hidden">
                            </div>
                            <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" required onchange="previewImage(this)" />
                            <p class="mt-1 text-sm text-gray-400">Accepted formats: JPEG, PNG, JPG. Max size: 2MB</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" class="text-gray-300" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="available">Available</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-secondary-button type="button" onclick="window.location.href='{{ route('admin.rooms.index') }}'" class="mr-3">
                                {{ __('Cancel') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Create Room') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
            }
        }

        // Format price input with dots
        const priceInput = document.getElementById('price_per_night');
        
        function formatNumber(num) {
            // Remove any non-digit characters first
            num = num.toString().replace(/\D/g, '');
            // Format with dots
            return num.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatNumber(str) {
            // Remove anything that's not a digit
            return str.replace(/\D/g, '');
        }
        
        // Format initial value if exists
        if (priceInput.value) {
            priceInput.value = formatNumber(priceInput.value);
        }

        priceInput.addEventListener('input', function(e) {
            // Store cursor position
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const length = this.value.length;
            
            // Format the value
            this.value = formatNumber(this.value);
            
            // Adjust cursor position if needed
            const newLength = this.value.length;
            const diff = newLength - length;
            this.setSelectionRange(start + diff, end + diff);
        });

        // Handle form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get raw numeric value
            const numericValue = unformatNumber(priceInput.value);
            
            if (!numericValue || parseInt(numericValue) <= 0) {
                alert('Harga harus berupa angka positif');
                return;
            }
            
            // Set the raw numeric value before submit
            priceInput.value = numericValue;
            
            // Submit the form
            this.submit();
        });
    </script>
</x-admin-layout> 