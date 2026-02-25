// Function to fetch payment details from the server
function fetchPaymentDetails() {
    // TODO: Replace with actual API endpoint
    fetch('/api/payment-details')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-amount').textContent = data.totalAmount;
            document.getElementById('commission').textContent = data.commission;
            document.getElementById('total-with-commission').textContent = data.totalWithCommission;
            document.getElementById('invoice-id').textContent = data.invoiceId;
            document.getElementById('next-payment-date').textContent = data.nextPaymentDate;
        })
        .catch(error => console.error('Error fetching payment details:', error));
}

// Function to handle form submission
function handleFormSubmit(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const paymentData = Object.fromEntries(formData.entries());

    // TODO: Replace with actual API endpoint
    fetch('/api/process-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(paymentData),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Payment successful!');
        } else {
            alert('Payment failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error processing payment:', error);
        alert('An error occurred. Please try again later.');
    });
}

// Function to handle TIGOlipa payment
function handleTIGOlipaPayment() {
    // TODO: Implement TIGOlipa payment logic
    alert('TIGOlipa payment initiated. Please complete the process on your mobile device.');
}

// Function to handle bank payment
function handleBankPayment() {
    // TODO: Implement bank payment logic
    alert('Bank payment initiated. Please complete the transfer using the provided account number.');
}

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    fetchPaymentDetails();

    const paymentForm = document.getElementById('payment-form');
    paymentForm.addEventListener('submit', handleFormSubmit);

    const payTIGOlipaButton = document.getElementById('pay-tigolipa');
    payTIGOlipaButton.addEventListener('click', handleTIGOlipaPayment);

    const payBankButton = document.getElementById('pay-bank');
    payBankButton.addEventListener('click', handleBankPayment);

    // Toggle payment methods
    const paymentMethods = document.querySelectorAll('.nav-link');
    paymentMethods.forEach(method => {
        method.addEventListener('click', (event) => {
            event.preventDefault();
            const methodName = event.target.textContent.toLowerCase();
            document.getElementById('payment-form').style.display = methodName === 'credit card' ? 'block' : 'none';
            document.getElementById('mobile-payment').style.display = methodName === 'mobile payment' ? 'block' : 'none';
            document.getElementById('bank-payment').style.display = methodName === 'bank payment' ? 'block' : 'none';
        });
    });
});

// TODO: Replace '/api/socket' with the actual WebSocket endpoint
const socket = new WebSocket('ws://your-laravel-backend.com/api/socket');

socket.onopen = function(e) {
    console.log("[open] Connection established");
};

socket.onmessage = function(event) {
    console.log(`[message] Data received from server: ${event.data}`);
    // Handle real-time updates here
};

socket.onclose = function(event) {
    if (event.wasClean) {
        console.log(`[close] Connection closed cleanly, code=${event.code} reason=${event.reason}`);
    } else {
        console.log('[close] Connection died');
    }
};

socket.onerror = function(error) {
    console.log(`[error] ${error.message}`);
};