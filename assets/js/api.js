/**
 * API Client for TruckLoad
 */

const API = {
    baseUrl: window.location.origin + '/api',
    token: localStorage.getItem('token') || '',
    
    async request(endpoint, method = 'GET', data = null) {
        const options = {
            method,
            headers: { 'Content-Type': 'application/json' }
        };
        
        if (this.token) {
            options.headers['Authorization'] = `Bearer ${this.token}`;
        }
        
        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }
        
        try {
            const response = await fetch(endpoint, options);
            const result = await response.json();
            if (!response.ok) throw new Error(result.error || 'Request failed');
            return result;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },
    
    auth: {
        register(data) {
            return API.request(API.baseUrl + '/auth.php?action=register', 'POST', data);
        },
        login(email, password) {
            return API.request(API.baseUrl + '/auth.php?action=login', 'POST', { email, password });
        },
        logout() {
            localStorage.removeItem('token');
            window.location.href = '/index.php';
        }
    },
    
    rides: {
        create(data) {
            return API.request(API.baseUrl + '/rides.php', 'POST', data);
        },
        getAvailable() {
            return API.request(API.baseUrl + '/rides.php?action=available');
        },
        getMyRides() {
            return API.request(API.baseUrl + '/rides.php?action=my_rides');
        },
        getDetails(id) {
            return API.request(API.baseUrl + `/rides.php?action=details&id=${id}`);
        },
        accept(id) {
            return API.request(API.baseUrl + '/rides.php', 'PUT', { id, action: 'accept' });
        },
        complete(id) {
            return API.request(API.baseUrl + '/rides.php', 'PUT', { id, action: 'complete' });
        },
        cancel(id) {
            return API.request(API.baseUrl + '/rides.php', 'PUT', { id, action: 'cancel' });
        }
    },
    
    payments: {
        createIntent(rideId, amount) {
            return API.request(API.baseUrl + '/payments.php?action=create_intent', 'POST', { ride_id: rideId, amount });
        },
        confirm(paymentId) {
            return API.request(API.baseUrl + '/payments.php?action=confirm', 'POST', { payment_id: paymentId });
        },
        getHistory() {
            return API.request(API.baseUrl + '/payments.php?action=history');
        }
    },
    
    chat: {
        send(rideId, recipientId, message) {
            return API.request(API.baseUrl + '/chat.php?action=send', 'POST', { ride_id: rideId, recipient_id: recipientId, message });
        },
        getHistory(rideId) {
            return API.request(API.baseUrl + `/chat.php?action=history&ride_id=${rideId}`);
        }
    },
    
    ratings: {
        submit(rideId, ratedUserId, rating, comment) {
            return API.request(API.baseUrl + '/ratings.php', 'POST', { ride_id: rideId, rated_user_id: ratedUserId, rating, comment });
        },
        getUser(userId) {
            return API.request(API.baseUrl + `/ratings.php?action=user&user_id=${userId}`);
        }
    }
};
