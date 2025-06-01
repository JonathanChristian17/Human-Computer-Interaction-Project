<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cahaya Resort')</title>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Midtrans -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @yield('head')
</head>
<body class="bg-white">
    <!-- Include Navbar -->
    @include('components.navbar')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Base Scripts -->
    <script>
        // Add CSRF token to all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Transaction related functions
        window.showTransactionDetails = function(id) {
            fetch(`/transactions/${id}`)
                .then(response => response.json())
                .then(data => {
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
                                <p class="mb-2"><strong>Check-in:</strong> ${data.booking.check_in_date}</p>
                                <p class="mb-2"><strong>Check-out:</strong> ${data.booking.check_out_date}</p>
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
                })
                .catch(error => {
                    console.error('Error fetching transaction details:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load transaction details'
                    });
                });
        };

        window.payTransaction = function(id) {
            Swal.fire({
                title: 'Process Payment',
                text: 'Are you sure you want to proceed with the payment?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed',
                cancelButtonText: 'No, cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/transactions/${id}/pay`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Payment Successful',
                                        text: 'Your payment has been processed successfully!'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                },
                                onPending: function(result) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Payment Pending',
                                        text: 'Please complete your payment using the provided payment instructions.'
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                },
                                onError: function(result) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Payment Failed',
                                        text: 'An error occurred while processing your payment. Please try again.'
                                    });
                                },
                                onClose: function() {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Payment Cancelled',
                                        text: 'You closed the payment window. Please try again if you wish to complete the payment.'
                                    });
                                }
                            });
                        } else {
                            throw new Error(data.message || 'Failed to initiate payment');
                        }
                    })
                    .catch(error => {
                        console.error('Payment Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to process payment. Please try again.'
                        });
                    });
                }
            });
        };

        window.cancelTransaction = function(id) {
            Swal.fire({
                title: 'Cancel Transaction',
                text: 'Are you sure you want to cancel this transaction?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, cancel it',
                cancelButtonText: 'No, keep it',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/transactions/${id}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: 'Transaction has been cancelled successfully.'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Failed to cancel transaction');
                        }
                    })
                    .catch(error => {
                        console.error('Cancel Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to cancel transaction. Please try again.'
                        });
                    });
                }
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize any existing panels
            if (window.location.pathname.includes('/rooms')) {
                showRooms();
            }

            // Show any flash messages using SweetAlert2
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session("success") }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session("error") }}',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        });
    </script>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html> 