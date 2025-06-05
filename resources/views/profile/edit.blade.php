<x-app-layout>
    <div x-data="profileForm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex flex-col items-center space-y-6 mb-8 md:flex-row md:space-y-0 md:space-x-6">
                    <div class="shrink-0">
                        <img id="profile-photo" class="h-24 w-24 object-cover rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                    <div class="text-center md:text-left">
                        <h2 id="profile-name" class="text-2xl font-semibold" data-user-name>{{ Auth::user()->name }}</h2>
                        <p id="profile-email" class="text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="ml-auto flex space-x-3">
                        <button type="button" @click="showEditModal = true" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="display-name" value="{{ Auth::user()->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="display-email" value="{{ Auth::user()->email }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" id="display-phone" value="{{ Auth::user()->phone }}" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" value="************" class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div x-show="showEditModal" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-lg p-6 max-w-md w-full" @click.away="showEditModal = false">
                <h3 class="text-lg font-semibold mb-4">Edit Profile</h3>
                <form @submit.prevent="handleSubmit">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                            <input type="file" name="profile_photo" accept="image/*" class="w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" x-model="formData.name" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" x-model="formData.email" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" name="phone" x-model="formData.phone" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600"
                                :disabled="isSubmitting"
                                x-text="isSubmitting ? 'Saving...' : 'Save Changes'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('profileForm', () => ({
                showEditModal: false,
                isSubmitting: false,
                formData: {
                    name: '{{ Auth::user()->name }}',
                    email: '{{ Auth::user()->email }}',
                    phone: '{{ Auth::user()->phone }}'
                },
                showChangePasswordModal() {
                    const modalHtml = `
                        <div class="bg-white p-6 rounded-lg">
                            <h2 class="text-2xl font-semibold mb-6 text-center">Change Password</h2>
                            <form id="passwordForm" class="space-y-4">
                                <div class="space-y-2">
                                    <label class="block text-gray-700">Current Password</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="current_password" 
                                               name="current_password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                                               placeholder="Enter current password">
                                        <button type="button" 
                                                onclick="togglePassword('current_password')"
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-gray-700">New Password</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="new_password" 
                                               name="password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                                               placeholder="Enter new password">
                                        <button type="button" 
                                                onclick="togglePassword('new_password')"
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-gray-700">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" 
                                               id="password_confirmation" 
                                               name="password_confirmation" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                                               placeholder="Confirm new password">
                                        <button type="button" 
                                                onclick="togglePassword('password_confirmation')"
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="flex justify-center space-x-3 mt-6">
                                    <button type="submit" class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                                        Update Password
                                    </button>
                                    <button type="button" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors" onclick="Swal.close()">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    `;

                    Swal.fire({
                        html: modalHtml,
                        showConfirmButton: false,
                        showCloseButton: true,
                        padding: '0',
                        customClass: {
                            popup: 'rounded-xl shadow-xl',
                            closeButton: 'text-gray-500 hover:text-gray-700'
                        }
                    });

                    // Add form submit handler
                    document.getElementById('passwordForm').addEventListener('submit', async (e) => {
                        e.preventDefault();
                        const form = e.target;
                        const formData = new FormData(form);

                        // Disable submit button and show loading state
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

                        try {
                            const response = await fetch('{{ route("profile.password.update") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    current_password: formData.get('current_password'),
                                    password: formData.get('password'),
                                    password_confirmation: formData.get('password_confirmation')
                                })
                            });

                            const data = await response.json();

                            if (response.status === 422) {
                                // Handle validation errors
                                if (data.errors) {
                                    const errorMessages = [];
                                    
                                    if (data.errors.current_password) {
                                        errorMessages.push('Current password is incorrect');
                                    }
                                    
                                    if (data.errors.password) {
                                        data.errors.password.forEach(error => {
                                            if (error.includes('confirmed')) {
                                                errorMessages.push('New password and confirmation do not match');
                                            } else if (error.includes('different')) {
                                                errorMessages.push('New password must be different from current password');
                                            } else if (error.includes('min')) {
                                                errorMessages.push('New password must be at least 8 characters');
                                            } else {
                                                errorMessages.push(error);
                                            }
                                        });
                                    }
                                    
                                    throw new Error(errorMessages.join('\n'));
                                }
                                throw new Error(data.message || 'Validation failed');
                            }

                            if (!response.ok) {
                                throw new Error(data.message || 'Failed to update password');
                            }

                            // Close the password modal
                            Swal.close();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Password has been updated successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } catch (error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Password Update Failed',
                                html: error.message.replace(/\n/g, '<br>'),
                                confirmButtonColor: '#f97316'
                            });
                        } finally {
                            // Reset button state
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    });
                },
                async handleSubmit(e) {
                    try {
                        this.isSubmitting = true;
                        const form = e.target;
                        const formData = new FormData(form);
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'PATCH');
                        
                        // Add form data
                        formData.append('name', this.formData.name);
                        formData.append('email', this.formData.email);
                        formData.append('phone', this.formData.phone);

                        const response = await fetch('{{ route('profile.update') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();

                        if (data.success) {
                            // Update the profile information on the page
                            document.getElementById('profile-name').textContent = data.user.name;
                            document.getElementById('profile-email').textContent = data.user.email;
                            document.getElementById('display-name').value = data.user.name;
                            document.getElementById('display-email').value = data.user.email;
                            document.getElementById('display-phone').value = data.user.phone;
                            
                            // Update profile photo if available
                            const profilePhoto = document.getElementById('profile-photo');
                            if (profilePhoto && data.user.profile_photo_url) {
                                profilePhoto.src = data.user.profile_photo_url;
                            }
                            
                            // Close the modal
                            this.showEditModal = false;
                            
                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Profile updated successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong! Please try again.'
                        });
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            }));
        });

        // Function to toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
    @endpush
</x-app-layout>
