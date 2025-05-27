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