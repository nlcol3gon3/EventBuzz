<div class="container-fluid d-flex align-items-center justify-content-center py-5 bg-white">
    <div class="card mt-5 w-50">
        <div class="card-body">
            <!-- Guidance Banner -->
            <div class="alert alert-warning p-4 mb-4 rounded">
                <p>A prompt to confirm this deposit shall be sent to your phone.</p>
                <p>Allowed formats are strictly (with no + sign): 2547XXXXXXXX or 2541XXXXXXXX.</p>
            </div>

            <!-- Title -->
            <h2 class="card-title text-center mb-4 text-pink">M-Pesa Payment</h2>

            <!-- Alert messages for success and error -->
            <div id="errorAlert" class="alert alert-danger px-4 mb-4 rounded-md d-none">
                <!-- Error messages will appear here -->
            </div>
            <div id="successAlert" class="alert alert-success px-4 mb-4 rounded-md d-none">
                <!-- Success messages will appear here -->
            </div>

            <!-- Payment Form -->
            <form id="paymentForm">
                    <input type="number" id="bookingId" name="bookingId" value="<?= esc($booking['bookingId']) ?>" hidden>
                <div class="form-group mb-4">
                    <label for="phone" class="text-md font-medium text-gray-600">Enter Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter phone number (2547XXXXXXXX or 2541XXXXXXXX)" required class="form-control">
                </div>

                <div class="form-group mb-4">
                    <label for="amount" class="text-md font-medium text-gray-600">Enter Amount to Deposit</label>
                    <input type="number" id="amount" name="amount" placeholder="Enter amount" required class="form-control">
                </div>

                <button type="submit" id="makePayment" class="btn btn-success btn-block">
                    Make Deposit
                </button>
                <button disabled id="loadingButton" class="btn btn-block d-none" style="background-color: rgba(209, 104, 0, 1); color: rgba(255, 255, 255, 1);" type="button">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Processing Request
                </button>
            </form>
        </div>
    </div>
</div>
<script>
const serverName = "http://localhost:8080/";
// Select elements
const paymentForm = document.querySelector('#paymentForm');
const paymentButton = document.querySelector('#makePayment');
const loadingButton = document.querySelector('#loadingButton');
const errorAlert = document.querySelector('#errorAlert');
const successAlert = document.querySelector('#successAlert');

// Event listener for form submission
paymentForm.addEventListener('submit', event => {
    event.preventDefault(); // Prevent default form submission

    // Hide payment button and show loading button
    paymentButton.classList.add('d-none');
    loadingButton.classList.remove('d-none');

    // Extract values from form fields
    const phoneNumber = document.querySelector('#phone').value;
    const amount = document.querySelector('#amount').value;
    const orderId = document.querySelector('#bookingId').value;

    // Prepare data for POST request
    const data = {
        phoneNumber,
        amount,
        orderId,
    };

    // Initiate STK/IN Push
    fetch(`${serverName}bookings/stk-push`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
            Authorization: `Bearer ${localStorage.getItem('jwt')}`,
        },
        body: JSON.stringify(data),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.message) {
            // Display intermediate success message
            console.log(data.message);
            displaySuccess(successAlert, data.message);

            // Perform STK query status check
            return checkStatus(data.checkoutRequestId);
        } else if (data.error) {
            console.log(data.error);
            displayError(errorAlert, data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        displayError(errorAlert, 'Transaction failed. Try again later.');
    })
    .finally(() => {
        // Show payment button and hide loading button after request completes
        paymentButton.classList.remove('d-none');
        loadingButton.classList.add('d-none');
    });
});

// Function to check STK status
function checkStatus(checkoutRequestId) {
	function queryStatus() {
        return fetch(`${serverName}bookings/stk-query`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                Authorization: `Bearer ${localStorage.getItem('jwt')}`,
            },
            body: JSON.stringify({checkoutRequestId}),
        });
    }
    function processStatus(statusData) {
        if (statusData.error) {
            console.error(statusData.error);
            displayError(errorAlert, statusData.error);
        } else if (statusData.message) {
            displaySuccess(successAlert, statusData.message);
        } else {
            return new Promise(resolve => setTimeout(resolve, 5000))
                .then(queryStatus)
                .then(response => response.json())
                .then(processStatus)
                .catch(error => {
                    console.error('Error:', error);
                    displayError(errorAlert, 'An expected error occurred while checking the transaction request status');
                });
        }
    }

    // Query STK Status
    return queryStatus()
        .then(response => response.json())
        .then(processStatus)
        .catch(error => {
            console.error('Error:', error);
            displayError(errorAlert, 'An expected error occurred while checking the transaction request status');
        });
}

// Function to display success message
function displaySuccess(successAlertDiv, successMessage) {
    successAlertDiv.textContent = successMessage;
    successAlertDiv.classList.remove('d-none');
    setTimeout(() => {
        successAlertDiv.classList.add('d-none');
    }, 10000); // Hide after 10 seconds
}

// Function to display error message
function displayError(errorAlertDiv, errorMessage) {
    errorAlertDiv.textContent = errorMessage;
    errorAlertDiv.classList.remove('d-none');
    setTimeout(() => {
        errorAlertDiv.classList.add('d-none');
    }, 10000);
}
</script>
