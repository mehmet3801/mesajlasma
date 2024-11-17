function fetchMessages() {
    fetch('chat.php?action=getMessages')
        .then(response => response.json())
        .then(data => {
            const messages = document.getElementById('messages');
            messages.innerHTML = '';
            data.forEach(msg => {
                messages.innerHTML += `<p><strong>${msg.username}:</strong> ${msg.message}</p>`;
            });
            messages.scrollTop = messages.scrollHeight;
        });
}

function sendMessage() {
    const username = document.getElementById('username').value;
    const message = document.getElementById('message').value;

    if (username && message) {
        fetch('chat.php?action=sendMessage', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&message=${encodeURIComponent(message)}`
        }).then(() => {
            document.getElementById('message').value = '';
            fetchMessages();
        });
    }
}

// Mesajları periyodik olarak yenile
setInterval(fetchMessages, 2000);
fetchMessages();
