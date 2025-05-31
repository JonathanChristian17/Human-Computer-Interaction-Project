/*!
 * Echo configuration
 */

// Initialize Echo
if (typeof window.pusherClient === 'undefined') {
    window.pusherClient = new Pusher(window.PUSHER_APP_KEY, {
        cluster: window.PUSHER_APP_CLUSTER,
        forceTLS: true
    });
} 