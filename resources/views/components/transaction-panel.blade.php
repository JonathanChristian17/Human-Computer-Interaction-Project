<!-- Transaction Panel -->
<div x-data="transactionPanel" 
     x-init="init()"
     @keydown.window.escape="open = false">
    <div x-show="open" class="relative z-50">
        <!-- Background backdrop -->
        <div x-show="open" 
            x-transition:enter="ease-in-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="open"
                        x-transition:enter="transform transition ease-in-out duration-500"
                        x-transition:enter-start="translate-y-full"
                        x-transition:enter-end="translate-y-0"
                        x-transition:leave="transform transition ease-in-out duration-500"
                        x-transition:leave-start="translate-y-0"
                        x-transition:leave-end="translate-y-full"
                        class="pointer-events-auto w-screen max-w-4xl">
                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                            <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900">Transaction History</h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button type="button" class="-m-2 p-2 text-gray-400 hover:text-gray-500" @click="open = false">
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flow-root">
                                        <div class="divide-y divide-gray-200" id="transactionList">
                                            <!-- Transaction list will be loaded here -->
                                            <div class="text-center py-4">
                                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500 mx-auto"></div>
                                                <p class="mt-2 text-gray-600">Loading transactions...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Remove hash from URL if present
if (window.location.hash) {
    const cleanUrl = window.location.protocol + '//' + window.location.host + window.location.pathname + window.location.search;
    window.history.replaceState({}, document.title, cleanUrl);
}

// Global variables
window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';
window.PUSHER_APP_CLUSTER = '{{ config('broadcasting.connections.pusher.options.cluster') }}';

// Store channel subscriptions globally
window.channelSubscriptions = window.channelSubscriptions || {};

// Function to safely subscribe to a channel
function safeSubscribe(channelName, events = {}) {
    // Unsubscribe if already subscribed
    if (window.channelSubscriptions[channelName]) {
        window.pusherClient.unsubscribe(channelName);
    }
    
    // Create new subscription
    const channel = window.pusherClient.subscribe(channelName);
    window.channelSubscriptions[channelName] = channel;
    
    // Bind events
    Object.entries(events).forEach(([event, callback]) => {
        channel.bind(event, callback);
    });
    
    return channel;
}

document.addEventListener('alpine:init', () => {
    Alpine.data('transactionPanel', () => ({
        open: false,
        init() {
            // Make the instance available globally for event handlers
            window.transactionPanel = this;
            
            // Check URL parameters to open transaction panel
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('panel') === 'transactions') {
                this.open = true;
                this.loadTransactions();
            }
            
            // Listen for custom event to open transaction panel
            window.addEventListener('open-transaction-panel', () => {
                this.open = true;
                this.loadTransactions();
            });

            // Listen for booking status changes
            window.addEventListener('booking-status-changed', () => {
                this.loadTransactions();
            });

            // Initialize Pusher channels
            this.initializePusherChannels();
        },
        initializePusherChannels() {
            // Subscribe to bookings channel
            safeSubscribe('bookings', {
                'booking.status.changed': (data) => {
                    console.log('Received real-time update:', data);
                    this.loadTransactions();
                }
            });

            // Subscribe to payments channel
            safeSubscribe('payments', {
                'App\\Events\\PaymentStatusUpdated': (data) => {
                    console.log('Payment status updated:', data);
                    this.loadTransactions();
                }
            });
        },
        async loadTransactions() {
            try {
                const response = await fetch('{{ route("transactions.index") }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to load transactions');
                }
                
                const data = await response.json();
                if (data.html) {
                    document.getElementById('transactionList').innerHTML = data.html;

                    // Style cancel buttons
                    document.querySelectorAll('.cancel-transaction-btn').forEach(btn => {
                        btn.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white', 'font-semibold');
                    });

                    // Add event listener for Book a Room Now button
                    const bookRoomBtn = document.querySelector('.book-room-btn');
                    if (bookRoomBtn) {
                        bookRoomBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            this.open = false; // Close transaction panel
                            window.dispatchEvent(new CustomEvent('show-room-panel')); // Show room panel
                        });
                    }
                } else {
                    throw new Error('Invalid response format');
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
                document.getElementById('transactionList').innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 bg-gray-50 rounded-lg">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Failed to Load Transactions</h3>
                        <p class="mt-1 text-sm text-gray-500">There was an error loading your transactions. Please try again.</p>
                        <button @click="loadTransactions()" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Retry
                        </button>
                    </div>
                `;
            }
        },
        async showDetails(id) {
            try {
                const response = await fetch(`/transactions/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to load transaction details');
                }

                const data = await response.json();
                
                // Format dates
                const checkIn = data.booking ? new Date(data.booking.check_in_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
                const checkOut = data.booking ? new Date(data.booking.check_out_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : '-';
                const createdAt = new Date(data.created_at).toLocaleString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });

                // Show the details in a SweetAlert2 modal
                Swal.fire({
                    title: `Order #${data.order_id}`,
                    html: `
                        <div class="text-left">
                            <p class="mb-2"><strong>Transaction ID:</strong> ${data.transaction_id || '-'}</p>
                            <p class="mb-2"><strong>Status:</strong> ${data.payment_status}</p>
                            <p class="mb-2"><strong>Amount:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.gross_amount)}</p>
                            <p class="mb-2"><strong>Payment Method:</strong> ${data.payment_type || '-'}</p>
                            <p class="mb-2"><strong>Payment Code:</strong> ${data.payment_code || '-'}</p>
                            <hr class="my-3">
                            <p class="mb-2"><strong>Check-in:</strong> ${checkIn}</p>
                            <p class="mb-2"><strong>Check-out:</strong> ${checkOut}</p>
                            <p class="mb-2"><strong>Duration:</strong> ${data.booking.duration} night(s)</p>
                            <p class="mb-2"><strong>Guest Name:</strong> ${data.booking.guest_name}</p>
                            <p class="mb-2"><strong>Email:</strong> ${data.booking.email}</p>
                            <p class="mb-2"><strong>Phone:</strong> ${data.booking.phone}</p>
                        </div>
                    `,
                    width: '600px',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#3085d6'
                });
            } catch (error) {
                console.error('Error fetching transaction details:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load transaction details'
                });
            }
        },
        async payTransaction(id) {
            try {
                // Check if transaction has deposit status
                const transaction = await this.getTransactionDetails(id);
                if (transaction && transaction.payment_status === 'deposit') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Deposit Already Paid',
                        text: 'This booking has a deposit payment. The remaining amount should be paid at check-in.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                const result = await Swal.fire({
                    title: 'Process Payment',
                    text: 'Are you sure you want to proceed with the payment?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'No, cancel',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                });

                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we initialize your payment',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const response = await fetch(`/transactions/${id}/pay`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to process payment');
                    }

                    if (!data.success || !data.snap_token) {
                        throw new Error('Failed to initialize payment');
                    }

                    // Close loading dialog
                    Swal.close();
                    
                    // Open Midtrans Snap popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: async (result) => {
                            try {
                                // Show success message
                                await Swal.fire({
                                    icon: 'success',
                                    title: 'Payment Successful',
                                    text: 'Your payment has been processed successfully!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Refresh transaction list
                                this.loadTransactions();
                            } catch (error) {
                                console.error('Payment completion error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.message || 'Failed to complete payment. Please check your transaction history.'
                                });
                            }
                        },
                        onPending: (result) => {
                            // Save payment details to localStorage
                            localStorage.setItem('lastPaymentMethod', result.payment_type);
                            localStorage.setItem('lastTransactionId', id);
                            
                            Swal.fire({
                                icon: 'info',
                                title: 'Complete Your Payment',
                                text: 'Please complete your payment using the provided payment instructions.',
                                showConfirmButton: true,
                                confirmButtonText: 'View Payment Instructions',
                            }).then(() => {
                                this.loadTransactions();
                            });
                        },
                        onError: (result) => {
                            console.error('Midtrans payment error:', result);
                            Swal.fire({
                                icon: 'error',
                                title: 'Payment Failed',
                                text: 'An error occurred while processing your payment. Please try again.'
                            });
                        },
                        onClose: () => {
                            // Check if payment method was selected
                            const lastPaymentMethod = localStorage.getItem('lastPaymentMethod');
                            const lastTransactionId = localStorage.getItem('lastTransactionId');
                            
                            if (lastPaymentMethod && lastTransactionId === id.toString()) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Payment Method Selected',
                                    text: 'Your order has been confirmed. Please check your transaction history to continue the payment.',
                                    showConfirmButton: true,
                                    confirmButtonText: 'View Transactions',
                                }).then(() => {
                                    // Clear stored payment info
                                    localStorage.removeItem('lastPaymentMethod');
                                    localStorage.removeItem('lastTransactionId');
                                    this.loadTransactions();
                                });
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Payment Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to process payment. Please try again.'
                });
            }
        },
        async cancelTransaction(id) {
            try {
                // Check if transaction has deposit status
                const transaction = await this.getTransactionDetails(id);
                if (transaction && transaction.payment_status === 'deposit') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Cannot Cancel',
                        text: 'This booking has a deposit payment and cannot be cancelled.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                const result = await Swal.fire({
                    title: 'Cancel Transaction',
                    text: 'Are you sure you want to cancel this transaction?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel it',
                    cancelButtonText: 'No, keep it',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6'
                });

                if (result.isConfirmed) {
                    const response = await fetch(`/transactions/${id}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Failed to cancel transaction');
                    }

                    // Show success message
                    await Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Transaction cancelled successfully',
                        timer: 1500,
                        showConfirmButton: false,
                        willClose: () => {
                            // Remove any event listeners that might prevent refresh
                            window.onbeforeunload = null;
                            
                            // Force a clean reload to the URL without hash
                            const cleanUrl = window.location.protocol + '//' + window.location.host + '/?panel=transactions';
                            window.location.replace(cleanUrl);
                        }
                    });
                }
            } catch (error) {
                console.error('Cancel Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to cancel transaction. Please try again.',
                    confirmButtonColor: '#f97316'
                });
            }
        },
        async getTransactionDetails(id) {
            try {
                const response = await fetch(`/transactions/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to get transaction details');
                }

                const data = await response.json();
                return data.data;
            } catch (error) {
                console.error('Error fetching transaction details:', error);
                return null;
            }
        }
    }));
});
</script>
@endpush 