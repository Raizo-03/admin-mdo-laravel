@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <div class="border-b border-gray-700 pb-3">
        <h1 class="text-2xl font-bold text-white">Content Management</h1>
    </div>

    <div class="border-b border-gray-700 pb-8">
    <h1 class="text-2xl text-white">Chat</h1>
    </div>

    <div class="flex flex-col md:flex-row max-w-8xl h-[700px] mx-auto bg-white shadow-lg rounded-lg">
        <!-- Side Panel (User List) -->
        <div class="md:w-1/3 border-r p-4 bg-gray-100">
            <!-- Search Bar -->
            <div class="mb-4">
                <input type="text" id="search-user" placeholder="Search users..." 
                    class="w-full p-2 border rounded">
            </div>

            <!-- User List -->
            <div class="h-96 overflow-y-auto">
                <ul id="user-list">
                    @foreach($users as $user)
                    <li class="p-2 bg-white mb-2 rounded-lg cursor-pointer flex items-center justify-between hover:bg-gray-200"
                        data-email="{{ $user->email }}"
                        onclick="openChat('{{ $user->email }}')">
                        <div class="flex items-center">
                            <div class="mr-3">
                                <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                    {{ strtoupper(substr($user->email, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold">{{ $user->email }}</p>
                                <span class="text-sm text-gray-500 last-message">Loading...</span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span id="unread-count-{{ $user->email }}" 
                                  class="unread-count hidden bg-red-500 text-white text-xs rounded-full px-2 py-1 mr-2">
                                0
                            </span>
                            <button class="text-gray-400 hover:text-red-500" 
                                    onclick="hideUser(event, this, '{{ $user->email }}')">✖</button>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Chat Panel (Existing code remains the same) -->
            <!-- Chat Panel -->
            <div class="flex-1 relative">
                <!-- Chat Header -->
                <div class="p-4 border-b flex items-center bg-blue-600 text-white">
                    <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A5.99 5.99 0 016 15.333V14a6 6 0 0112 0v1.333a5.99 5.99 0 01.879 2.471M15 9a3 3 0 00-6 0"></path>
                    </svg>
                    <h2 class="text-lg font-semibold" id="chat-user-name">Select a user</h2>
                </div>

                <!-- Chat Messages -->
                <div id="chat-box" class="p-4 h-[calc(100%-120px)] overflow-y-auto bg-gray-50">
                    <p class="text-gray-400 text-center">Select a user to start chat</p>
                </div>

                <!-- Chat Input - Positioned at the bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t flex">
                    <input id="chat-input" type="text" class="w-full p-2 border rounded" placeholder="Type a message..." disabled>
                    <button id="send-btn" class="bg-[#111C4E] text-white px-4 py-2 rounded ml-2" disabled>Send</button>
                </div>
            </div>
    </div>
</div>

<script>
    let currentSelectedUserEmail = null;

    function openChat(userEmail) {
        currentSelectedUserEmail = userEmail;
        document.getElementById('chat-user-name').innerText = userEmail;
        
        // Reset unread count for this user
        const unreadCountElement = document.getElementById(`unread-count-${userEmail}`);
        if (unreadCountElement) {
            unreadCountElement.classList.add('hidden');
            unreadCountElement.textContent = '0';
        }

        document.getElementById('chat-box').innerHTML = `
            <div class="text-center text-gray-400">Loading messages...</div>
        `;
        document.getElementById('chat-input').disabled = false;
        document.getElementById('send-btn').disabled = false;

        // Fetch messages for the selected user
        fetch(`/messages/${encodeURIComponent(userEmail)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Non-JSON response:', text);
                    throw new Error(`HTTP error! status: ${response.status}`);
                });
            }
            
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                throw new Error('Received non-JSON response');
            }
        })
        .then(messages => {
            let chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; // Clear previous messages

            if (!messages || messages.length === 0) {
                chatBox.innerHTML = `
                    <div class="text-center text-gray-400">No messages found. Start a conversation!</div>
                `;
                return;
            }

            // Update last message for the user
            const lastMessage = messages[messages.length - 1];
            const userListItem = document.querySelector(`#user-list li[data-email="${userEmail}"]`);
            if (userListItem) {
                const lastMessageSpan = userListItem.querySelector('.last-message');
                if (lastMessageSpan) {
                    lastMessageSpan.textContent = lastMessage.message.length > 20 
                        ? lastMessage.message.substring(0, 20) + '...' 
                        : lastMessage.message;
                }
            }

          messages.forEach(msg => {
    let messageDiv = document.createElement('div');
    messageDiv.classList.add('mb-3');
    
    // Determine if message is sent or received
    if (msg.sender_email === userEmail) {
        // Messages from user to admin
        messageDiv.innerHTML = `
            <div class="text-left">
                <div class="inline-block bg-gray-300 text-black p-2 rounded-lg mb-1">
                    ${msg.message}
                </div>
                <div class="text-xs text-gray-400 text-left">
                    ${formatTimestamp(msg.timestamp)}
                </div>
            </div>
        `;
    } else {
        // Messages from admin to user
        messageDiv.classList.add('text-right');
        messageDiv.innerHTML = `
            <div>
                <div class="inline-block bg-blue-500 text-white p-2 rounded-lg mb-1">
                    ${msg.message}
                </div>
                <div class="text-xs text-gray-400 text-right">
                    ${formatTimestamp(msg.timestamp)}
                </div>
            </div>
        `;
    }

    chatBox.appendChild(messageDiv);
});

            // Scroll to bottom
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => {
            console.error('Error fetching messages:', error);
            
            document.getElementById('chat-box').innerHTML = `
                <div class="text-center text-red-500">
                    Failed to load messages
                    <br>
                    <small>${error.message}</small>
                </div>
            `;
        });
    }

    // User Search Functionality
    document.getElementById('search-user').addEventListener('input', function () {
        let filter = this.value.toLowerCase();
        let users = document.querySelectorAll('#user-list li');

        users.forEach(user => {
            let email = user.querySelector('p').innerText.toLowerCase();
            if (email.includes(filter)) {
                user.style.display = 'flex';
            } else {
                user.style.display = 'none';
            }
        });
    });

    // Hide User from List
    function hideUser(event, btn, email) {
        event.stopPropagation();
        btn.parentElement.parentElement.style.display = 'none';
    }

    // Add send message functionality
    document.getElementById('send-btn').addEventListener('click', function() {
        sendMessage();
    });

    // Allow sending message by pressing Enter
    document.getElementById('chat-input').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    function sendMessage() {
        const messageInput = document.getElementById('chat-input');
        const message = messageInput.value.trim();

        // Check if a user is selected and message is not empty
        if (!currentSelectedUserEmail) {
            alert('Please select a user first');
            return;
        }

        if (!message) {
            alert('Please enter a message');
            return;
        }

        // Prepare data to send
        const formData = new FormData();
        formData.append('receiver_email', currentSelectedUserEmail);
        formData.append('message', message);

        // Send message via fetch
        fetch('/messages/send', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text);
                });
            }
            return response.json();
        })
        .then(data => {
            // Clear input
            messageInput.value = '';

            // Reload messages to show the new message
            openChat(currentSelectedUserEmail);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message: ' + error.message);
        });
    }

    // Check for new messages and update unread counts
    function checkForNewMessages() {
        fetch('/users-with-unread-messages', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(users => {
            // Reset all unread counts first
            document.querySelectorAll('.unread-count').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '0';
            });

            // Update unread counts and last messages
            users.forEach(user => {
                const userListItem = document.querySelector(`#user-list li[data-email="${user.sender_email}"]`);
                const unreadCountElement = document.getElementById(`unread-count-${user.sender_email}`);
                
                if (userListItem && unreadCountElement) {
                    // If this is not the currently selected chat
                    if (user.sender_email !== currentSelectedUserEmail) {
                        unreadCountElement.textContent = user.unread_count;
                        unreadCountElement.classList.remove('hidden');
                    }

                    // Update last message
                    const lastMessageSpan = userListItem.querySelector('.last-message');
                    if (lastMessageSpan) {
                        lastMessageSpan.textContent = user.last_message.length > 20 
                            ? user.last_message.substring(0, 20) + '...' 
                            : user.last_message;
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error checking for new messages:', error);
        });
    }

    // Check for new messages every 30 seconds
    setInterval(checkForNewMessages, 30000);

    // Initial check for new messages
    checkForNewMessages();

    function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}
</script>
@endsection