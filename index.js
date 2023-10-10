function refreshServerStatus() {
    const serverStatusElement = document.getElementById('serverStatus');
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            serverStatusElement.innerHTML = xhr.responseText;
        }
    };
    xhr.open('GET', 'serverStatus.php', true);
    xhr.send();
}

window.onload = refreshServerStatus;
setInterval(refreshServerStatus, 10000);