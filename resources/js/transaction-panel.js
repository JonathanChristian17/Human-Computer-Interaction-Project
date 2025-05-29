document.addEventListener('alpine:init', () => {
    Alpine.data('transactionPanel', () => ({
        open: false,

        init() {
            this.$watch('open', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });

            window.addEventListener('open-transaction-panel', () => {
                this.open = true;
            });

            // Listen for payment status updates
            window.Echo.channel('payments')
                .listen('PaymentStatusUpdated', (e) => {
                    console.log('Payment status updated:', e);
                    this.updateTransactionStatus(e.transactionData);
                });
        },

        updateTransactionStatus(data) {
            // Find the transaction element by order_id or transaction_id
            const transactionElement = document.querySelector(`[data-transaction-id="${data.transaction_id}"]`) || 
                                     document.querySelector(`[data-order-id="${data.order_id}"]`);
            
            if (transactionElement) {
                // Update status badges
                const statusBadge = transactionElement.querySelector('.transaction-status');
                const paymentStatusBadge = transactionElement.querySelector('.payment-status');
                
                if (statusBadge) {
                    statusBadge.textContent = data.transaction_status;
                    this.updateStatusBadgeStyle(statusBadge, data.transaction_status);
                }
                
                if (paymentStatusBadge) {
                    paymentStatusBadge.textContent = data.payment_status;
                    this.updateStatusBadgeStyle(paymentStatusBadge, data.payment_status);
                }

                // Hide/show action buttons based on new status
                const payButton = transactionElement.querySelector('.pay-button');
                const cancelButton = transactionElement.querySelector('.cancel-button');
                
                if (data.payment_status === 'paid' || data.transaction_status === 'settlement') {
                    if (payButton) payButton.style.display = 'none';
                    if (cancelButton) cancelButton.style.display = 'none';
                    
                    // Reload the page after a short delay to ensure everything is updated
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                // If we can't find the element, reload the page
                window.location.reload();
            }
        },

        updateStatusBadgeStyle(badge, status) {
            // Remove existing status classes
            badge.classList.remove(
                'bg-yellow-100', 'text-yellow-800',
                'bg-green-100', 'text-green-800',
                'bg-red-100', 'text-red-800',
                'bg-gray-100', 'text-gray-800'
            );

            // Add appropriate classes based on status
            switch (status) {
                case 'pending':
                    badge.classList.add('bg-yellow-100', 'text-yellow-800');
                    break;
                case 'paid':
                case 'settlement':
                case 'confirmed':
                    badge.classList.add('bg-green-100', 'text-green-800');
                    break;
                case 'cancelled':
                case 'deny':
                case 'expire':
                    badge.classList.add('bg-red-100', 'text-red-800');
                    break;
                default:
                    badge.classList.add('bg-gray-100', 'text-gray-800');
            }
        },

        async cancelTransaction(transactionId) {
            try {
                const response = await fetch(`/transactions/${transactionId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to cancel transaction');
                }

                // Refresh the page or update the UI
                window.location.reload();
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to cancel transaction. Please try again.');
            }
        },

        async payTransaction(transactionId) {
            try {
                const response = await fetch(`/transactions/${transactionId}/pay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to process payment');
                }

                // Refresh the page or update the UI
                window.location.reload();
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to process payment. Please try again.');
            }
        }
    }));
}); 