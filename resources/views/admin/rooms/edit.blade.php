<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-[#FFA500] leading-tight">
                {{ __('Edit Room') }}
            </h2>
            <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2 bg-[#2A2A2A] text-[#E0E0E0] rounded-lg hover:bg-[#333333] transition-colors duration-200 border border-[#333333] flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Rooms
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#1F1F1F] overflow-hidden shadow-sm sm:rounded-lg border border-[#333333]">
                <div class="p-6">
                    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 needs-confirm">
                        @csrf
                        @method('PUT')

                        <!-- Room Number -->
                        <div>
                            <label for="number" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Room Number') }}</label>
                            <input type="text" id="number" name="number" value="{{ old('number', $room->room_number) }}" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" />
                            <x-input-error :messages="$errors->get('number')" class="mt-2" />
                        </div>

                        <!-- Room Name -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Room Name') }}</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $room->name) }}" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Room Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Room Type') }}</label>
                            <select id="type" name="type" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm">
                                <option value="standard" {{ $room->type === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="deluxe" {{ $room->type === 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                                <option value="suite" {{ $room->type === 'suite' ? 'selected' : '' }}>Suite</option>
                                <option value="family" {{ $room->type === 'family' ? 'selected' : '' }}>Family</option>
                                <option value="luxury" {{ $room->type === 'luxury' ? 'selected' : '' }}>Luxury</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price_per_night" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Price per Night (Rp)') }}</label>
                            <input type="text" id="price_per_night" name="price_per_night" value="{{ old('price_per_night', number_format($room->price_per_night, 0, ',', '.')) }}" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" />
                            <x-input-error :messages="$errors->get('price_per_night')" class="mt-2" />
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Capacity (Persons)') }}</label>
                            <input type="number" id="capacity" name="capacity" min="1" value="{{ old('capacity', $room->capacity) }}" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm" />
                            <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="4" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm">{{ old('description', $room->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Room Image -->
                        <div>
                            <label for="image" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Room Image') }}</label>
                            <div class="mt-2 mb-4 space-y-4">
                                @if($room->image)
                                    <div>
                                        <p class="text-sm font-medium text-[#E0E0E0] mb-2">Current Image:</p>
                                        <img src="{{ asset('storage/images/' . $room->image) }}" alt="Current room image" class="w-48 h-32 object-cover rounded-lg">
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-[#E0E0E0] mb-2">New Image Preview:</p>
                                    <img id="preview" src="#" alt="Preview" class="w-48 h-32 object-cover rounded-lg hidden">
                                </div>
                            </div>
                            <input type="file" id="image" name="image" accept="image/*" 
                                class="mt-1 block w-full text-[#E0E0E0] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#FFA500] file:text-white hover:file:bg-[#ff8c1a] file:transition-colors file:duration-200"
                                onchange="previewImage(this)" />
                            <p class="mt-1 text-sm text-[#E0E0E0]">Leave empty to keep current image. Accepted formats: JPEG, PNG, JPG. Max size: 2MB</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-[#E0E0E0] mb-2">{{ __('Status') }}</label>
                            <select id="status" name="status" required
                                class="w-full px-5 py-3 bg-[#2A2A2A] border border-[#333333] rounded-lg text-[#E0E0E0] placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#FFA500] focus:border-transparent transition shadow-sm">
                                <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-4 border-t border-[#333333]">
                            <button type="button" 
                                onclick="window.location.href='{{ route('admin.rooms.index') }}'" 
                                class="px-4 py-2 bg-[#2A2A2A] text-[#E0E0E0] rounded-lg hover:bg-[#333333] transition-colors duration-200 border border-[#333333] mr-3">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-[#FFA500] text-white rounded-lg hover:bg-[#ff8c1a] transition-colors duration-200">
                                {{ __('Update Room') }}
                            </button>
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