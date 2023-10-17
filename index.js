function refreshServerStatus() {
    // Get the element where the server status will be displayed
    const serverStatusElement = document.getElementById('serverStatus');

    // Create a new XMLHttpRequest object for making the HTTP request
    const xhr = new XMLHttpRequest();

    // Define a function to handle the XMLHttpRequest's state change
    xhr.onreadystatechange = function () {
        // Check if the request has completed (readyState 4) and the response status is OK (status 200)
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Update the content of the 'serverStatusElement' with the response from the server
            serverStatusElement.innerHTML = xhr.responseText;
        }
    };

    // Open an asynchronous GET request to 'serverStatus.php'
    xhr.open('GET', 'serverStatus.php', true);

    // Send the GET request to the server
    xhr.send();
}

// Execute 'refreshServerStatus' when the window has finished loading
window.onload = refreshServerStatus;

// Set up an interval to call 'refreshServerStatus' every 60 seconds
setInterval(refreshServerStatus, 60000);
