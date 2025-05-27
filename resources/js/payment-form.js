document.addEventListener('DOMContentLoaded', function() {
    // Get the payment form element
    const paymentForm = document.getElementById('payment-form');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitButton = paymentForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = 'Processing...';
            
            try {
                // Get form data
                const formData = new FormData(paymentForm);
                const bookingId = formData.get('booking_id');
                const paymentMethod = formData.get('payment_method');
                
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Validate required fields
                if (!bookingId || !paymentMethod) {
                    throw new Error('Please select a payment method');
                }

                // Process the payment
                const response = await fetch('/bookings/process-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        booking_id: bookingId,
                        payment_method: paymentMethod
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Failed to process payment');
                }
                
                // If successful, open Snap payment popup
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '/bookings/finish?order_id=' + result.order_id;
                        },
                        onPending: function(result) {
                            window.location.href = '/bookings/finish?order_id=' + result.order_id;
                        },
                        onError: function(result) {
                            window.location.href = '/bookings/error';
                        },
                        onClose: function() {
                            // Reset button state when popup is closed
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                        }
                    });
                } else {
                    throw new Error('No payment token received');
                }
                
            } catch (error) {
                console.error('Payment Error:', error);
                
                // Show error message to user
                const errorDiv = document.getElementById('payment-error');
                if (errorDiv) {
                    errorDiv.textContent = error.message || 'Failed to process payment. Please try again.';
                    errorDiv.style.display = 'block';
                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } else {
                    alert(error.message || 'Failed to process payment. Please try again.');
                }
                
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });

        // Add visual feedback for payment method selection
        const radioButtons = paymentForm.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove active class from all labels
                radioButtons.forEach(rb => {
                    rb.closest('label').classList.remove('border-blue-500');
                    rb.closest('label').classList.add('border-gray-200');
                });
                
                // Add active class to selected label
                if (this.checked) {
                    this.closest('label').classList.remove('border-gray-200');
                    this.closest('label').classList.add('border-blue-500');
                }
            });
        });
    }
}); 