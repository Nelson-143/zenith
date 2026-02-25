@extends('layouts.tabler')

@section('title', 'Financial Assistance')

@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<div class="page-content">
    <div class="container-fluid">
       
     <div class="chat-container">
        <div class="chat-sidebar" id="sidebar">
    <button class="toggle-sidebar-btn" id="toggleSidebarBtn" style="display: none;">
        <i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-library-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 3m0 2.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667z" /><path d="M4.012 7.26a2.005 2.005 0 0 0 -1.012 1.737v10c0 1.1 .9 2 2 2h10c.75 0 1.158 -.385 1.5 -1" /><path d="M11 10h6" /><path d="M14 7v6" /></svg></i> Toggle Sidebar
    </button>
    <div class="sidebar-conversations">
        <div class="conversation active">
            <i class="fas fa-comment"></i>
            <span>Sales Forecast Analysis</span>
        </div>
        <div class="conversation">
            <i class="fas fa-comment"></i>
            <span>Stock Reorder Query</span>
        </div>
        <div class="conversation">
            <i class="fas fa-comment"></i>
            <span>Loan Assessment</span>
        </div>
    </div>
</div>

<script>
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        if (window.innerWidth < 768) {
            sidebar.style.display = 'none';
            toggleBtn.style.display = 'block';
        } else {
            sidebar.style.display = 'block';
            toggleBtn.style.display = 'none';
        }
    });

    document.getElementById('toggleSidebarBtn').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.style.display === 'none') {
            sidebar.style.display = 'block';
        } else {
            sidebar.style.display = 'none';
        }
    });

    window.dispatchEvent(new Event('resize'));
</script>


            <!-- Main Chat Area -->
            <div class="chat-main">
                <!-- Welcome Screen -->
                <div class="welcome-screen">
                    <h1>Wense Assistant</h1>
                    <div class="suggestion-grid">
                        <div class="suggestion-card">
                            <h3><i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart-bolt"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M13.5 17h-7.5v-14h-2" /><path d="M6 5l14 1l-.858 6.004m-2.642 .996h-10.5" /><path d="M19 16l-2 3h4l-2 3" /></svg></i> Sales Forecast</h3>
                            <p>"Analyze my sales trends for next quarter"</p>
                        </div>
                        <div class="suggestion-card">
                            <h3><i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cube-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M21 12.5v-4.509a1.98 1.98 0 0 0 -1 -1.717l-7 -4.008a2.016 2.016 0 0 0 -2 0l-7 4.007c-.619 .355 -1 1.01 -1 1.718v8.018c0 .709 .381 1.363 1 1.717l7 4.008a2.016 2.016 0 0 0 2 0" /><path d="M12 22v-10" /><path d="M12 12l8.73 -5.04" /><path d="M3.27 6.96l8.73 5.04" /><path d="M16 19h6" /><path d="M19 16v6" /></svg></i> Inventory</h3>
                            <p>"Should I reorder Product X?"</p>
                        </div>
                        <div class="suggestion-card">
                            <h3><i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-coins"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg></i> Loans</h3>
                            <p>"Evaluate my loan eligibility"</p>
                        </div>
                        <div class="suggestion-card">
                            <h3><i><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M15 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M9 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 20h14" /></svg></i> Profitability</h3>
                            <p>"Analyze my profit margins"</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="chat-messages" id="chatMessages">
                    <div class="message assistant">
                        <div class="message-content">
                            <p>Hello! I'm WenseAssist, an AI assistant for Wense Inventory. How can I help you today?</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="chat-input-container">
                    <div class="chat-input-box">
                        <textarea id="userMessage" placeholder="Ask about your business..." rows="1"></textarea>
                        <button class="send-button" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <p class="input-disclaimer">WenseAssist can make mistakes. Consider checking important information.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Add your CSS styles here */
    

/* Rsm */
.chat-container {
    display: flex;
    height: calc(100vh - 100px);
    background-color: #ffffff;
    margin-top: 20px;
}

/* Sidebar Styles */
.chat-sidebar {
    width: 260px;
    background-color: #202123;
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.new-chat-btn {
    background-color: #444654;
    color: white;
    border: 1px solid #565869;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 20px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.new-chat-btn:hover {
    background-color: #343541;
}

.sidebar-conversations {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.conversation {
    padding: 10px;
    color: #ffffff;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.conversation:hover {
    background-color: #343541;
}

.conversation.active {
    background-color: #343541;
}

/* Main Chat Area */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #ffffff;
}

/* Welcome Screen */
.welcome-screen {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px;
}

.welcome-screen h1 {
    font-size: 2rem;
    color: #202123;
    margin-bottom: 40px;
}

.suggestion-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    max-width: 900px;
}

.suggestion-card {
    background-color: #f7f7f8;
    padding: 20px;
    border-radius: 10px;
    cursor: pointer;
}

.suggestion-card:hover {
    background-color: #f0f0f1;
}

.suggestion-card h3 {
    font-size: 1.1rem;
    color: #202123;
    margin-bottom: 10px;
}

.suggestion-card p {
    color:rgb(0, 0, 0);
}

/* Chat Messages */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.message {
    display: flex;
    padding: 20px;
    margin-bottom: 10px;
}

.message.assistant {
    background-color: #f7f7f8;
}

.message-content {
    max-width: 800px;
    margin: 0 auto;
    width: 100%;
    color: black;
}

/* Chat Input */
.chat-input-container {
    border-top: 1px solid #e5e5e5;
    padding: 20px;
    background-color: #ffffff;
}

.chat-input-box {
    max-width: 800px;
    margin: 0 auto;
    position: relative;
}

textarea {
    width: 100%;
    padding: 12px 45px 12px 15px;
    border: 1px solid #e5e5e5;
    border-radius: 5px;
    resize: none;
    font-size: 16px;
    background-color: #ffffff;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
}

textarea:focus {
    outline: none;
    border-color: #2563eb;
}

.send-button {
    position: absolute;
    right: 10px;
    bottom: 8px;
    background: none;
    border: none;
    color: #2563eb;
    cursor: pointer;
    padding: 5px;
}

.send-button:hover {
    color: #1d4ed8;
}

.input-disclaimer {
    text-align: center;
    color: #6b6c7b;
    font-size: 12px;
    margin-top: 10px;
}
.message.assistant {
    background-color: #f7f7f8; /* Light gray background for bot messages */
    align-self: flex-start; /* Align to the left */
}

.message.user {
    background-color:rgb(151, 151, 151); /* Blue background for user messages */
    color: white;
    align-self: flex-end; /* Align to the right */
    /* Loading message style */
.message.assistant.loading .message-content p {
    color: #666;
    font-style: italic;
}

/* Error message style */
.message.assistant.error .message-content p {
    color: #ff4d4d;
    font-weight: bold;
}
}
</style>

<script>

function isGreeting(message) {
    const greetings = ['hi', 'hello', 'hey', 'good morning', 'good afternoon', 'good evening'];
    return greetings.some(greeting => message.toLowerCase().includes(greeting));
}

function handleGreeting(userName) {
    return `Hello, Apologies for the inconvenience. Service will be restored soon.`;
}

document.addEventListener("DOMContentLoaded", function () {
    const textarea = document.getElementById("userMessage");
    const sendButton = document.querySelector(".send-button");
    const chatMessages = document.getElementById("chatMessages");
    const welcomeScreen = document.querySelector(".welcome-screen");

    // Auto-resize textarea
    textarea.addEventListener("input", function () {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
    });

    // Send message function
    function sendMessage() {
        let userMessage = textarea.value.trim();
        if (!userMessage) return;

        // Hide welcome screen on first message
        if (welcomeScreen) {
            welcomeScreen.style.display = "none";
        }

        // Append user message
        addMessage(userMessage, "user");

        // Clear input
        textarea.value = "";
        textarea.style.height = "auto";

        // Check if the message is a greeting
        if (isGreeting(userMessage)) {
            addMessage(handleGreeting("User "), "assistant");
            return;
        }

        // Show loading indicator
        const loadingMessage = addMessage("FinAssist is typing...", "assistant loading");

        // Send request to backend
        fetch("{{ route('finassist.query') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ message: userMessage })
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading indicator
            chatMessages.removeChild(loadingMessage);

            if (data.response) {
                // Append bot response to the chat interface
                addMessage(data.response, "assistant");
            } else {
                addMessage("Something went wrong. Please try again later.", "assistant error");
            }
        })
        .catch(error => {
            console.error("Fetch Error:", error);
            chatMessages.removeChild(loadingMessage);
            addMessage("FinAssist is currently unavailable. Please try again later.", "assistant error");
        });
    }

    // Add message to chat
    function addMessage(content, type) {
        const messageDiv = document.createElement("div");
        messageDiv.className = `message ${type}`;
        messageDiv.innerHTML = `
            <div class="message-content">
                <p>${content}</p>
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return messageDiv; // Return the message element for later removal
    }

    // Event listeners
    sendButton.addEventListener("click", sendMessage);
    textarea.addEventListener("keydown", (e) => {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});

</script>
@endsection

