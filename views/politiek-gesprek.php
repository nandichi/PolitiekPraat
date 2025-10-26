<?php require_once __DIR__ . '/templates/header.php'; ?>

<style>
    /* Chat UI Styles */
    .chat-container {
        max-height: 600px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
    
    .message-bubble {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .typing-indicator {
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.6; }
        50% { opacity: 1; }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .progress-bar-fill {
        transition: width 0.3s ease-out;
    }
</style>

<!-- Hero Section -->
<div class="bg-gradient-to-br from-primary-dark via-primary to-secondary py-16 relative overflow-hidden">
    <!-- Ambient effects -->
    <div class="absolute top-10 right-10 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-96 h-96 bg-secondary/15 rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Badge -->
            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6">
                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                <span class="text-blue-100 text-sm font-medium">AI-Powered Stemadvies</span>
            </div>
            
            <!-- Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                <span class="bg-gradient-to-r from-secondary-light via-secondary to-primary-light bg-clip-text text-transparent">
                    Politiek Gesprek
                </span>
            </h1>
            
            <!-- Subtitle -->
            <p class="text-xl md:text-2xl text-blue-100 mb-8 leading-relaxed">
                Voer een intelligent gesprek over jouw politieke visie en ontdek welke partij echt bij je past
            </p>
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                    <div class="text-3xl font-bold text-white mb-2">20</div>
                    <div class="text-blue-200 text-sm">Intelligente Vragen</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                    <div class="text-3xl font-bold text-white mb-2">14</div>
                    <div class="text-blue-200 text-sm">Politieke Partijen</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6">
                    <div class="text-3xl font-bold text-white mb-2">AI</div>
                    <div class="text-blue-200 text-sm">Adaptieve Analyse</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Chat Interface -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Welcome Screen -->
            <div id="welcome-screen" class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Welkom bij Politiek Gesprek</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        In plaats van een standaard stemwijzer, voeren we een echt gesprek met je. 
                        Onze AI stelt 20 vragen die zich aanpassen aan jouw antwoorden en helpt je 
                        ontdekken welke partij het beste bij je past.
                    </p>
                </div>
                
                <!-- Features -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Adaptieve Vragen</h3>
                            <p class="text-gray-600 text-sm">Vragen passen zich aan op basis van jouw eerdere antwoorden</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Flexibel Antwoorden</h3>
                            <p class="text-gray-600 text-sm">Kies tussen multiple choice of uitgebreide open antwoorden</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Diepgaande Analyse</h3>
                            <p class="text-gray-600 text-sm">Krijg een uitgebreide analyse van jouw politieke profiel</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">10 Minuten</h3>
                            <p class="text-gray-600 text-sm">Het gesprek duurt ongeveer 10 minuten</p>
                        </div>
                    </div>
                </div>
                
                <!-- Start Button -->
                <div class="text-center">
                    <button id="start-conversation-btn" class="bg-gradient-to-r from-primary to-secondary text-white px-8 py-4 rounded-lg text-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        Start het Gesprek
                    </button>
                </div>
                
                <?php if ($activeSession): ?>
                <div class="mt-6 p-4 bg-blue-50 rounded-lg text-center">
                    <p class="text-blue-800 mb-2">Je hebt een actief gesprek vanaf vraag <?= $activeSession['current_question_index'] + 1 ?></p>
                    <button id="resume-conversation-btn" class="text-blue-600 hover:text-blue-800 font-semibold underline" data-session="<?= $activeSession['session_id'] ?>">
                        Ga verder waar je was gebleven
                    </button>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Chat Interface (Hidden Initially) -->
            <div id="chat-interface" class="hidden">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <!-- Progress Bar -->
                    <div class="bg-gray-200 h-2">
                        <div id="progress-bar" class="bg-gradient-to-r from-primary to-secondary h-full progress-bar-fill" style="width: 0%"></div>
                    </div>
                    
                    <!-- Chat Header -->
                    <div class="bg-gradient-to-r from-primary to-secondary p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold">PolitiekPraat AI</div>
                                    <div class="text-xs text-blue-100">Online</div>
                                </div>
                            </div>
                            <div id="question-counter" class="text-sm font-medium">
                                Vraag <span id="current-question-num">1</span> van 20
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat Messages -->
                    <div id="chat-messages" class="chat-container p-6 space-y-4 bg-gray-50" style="min-height: 400px; max-height: 500px;">
                        <!-- Messages will be added here dynamically -->
                    </div>
                    
                    <!-- Input Area -->
                    <div id="input-area" class="p-4 bg-white border-t border-gray-200">
                        <!-- Multiple Choice Options -->
                        <div id="multiple-choice-input" class="hidden space-y-2">
                            <div id="choice-buttons" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <!-- Buttons will be added dynamically -->
                            </div>
                        </div>
                        
                        <!-- Open Text Input -->
                        <div id="open-text-input" class="hidden">
                            <textarea 
                                id="user-text-input" 
                                rows="3" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-primary focus:border-transparent resize-none"
                                placeholder="Typ hier jouw antwoord..."
                            ></textarea>
                            <div class="mt-2 flex justify-end">
                                <button id="submit-text-btn" class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-lg font-medium transition-colors">
                                    Verstuur
                                </button>
                            </div>
                        </div>
                        
                        <!-- Loading State -->
                        <div id="loading-state" class="hidden flex items-center justify-center space-x-3 py-4">
                            <div class="typing-indicator flex space-x-2">
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                            </div>
                            <span class="text-gray-500">AI denkt na...</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Results Screen (Hidden Initially) -->
            <div id="results-screen" class="hidden">
                <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">Gesprek Voltooid!</h2>
                        <p class="text-lg text-gray-600">Hier zijn jouw persoonlijke resultaten</p>
                    </div>
                    
                    <!-- Results Content -->
                    <div id="results-content">
                        <!-- Will be populated with results -->
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
// State Management
let conversationState = {
    sessionId: null,
    currentQuestion: 1,
    totalQuestions: 20,
    answers: []
};

// DOM Elements
const welcomeScreen = document.getElementById('welcome-screen');
const chatInterface = document.getElementById('chat-interface');
const resultsScreen = document.getElementById('results-screen');
const startBtn = document.getElementById('start-conversation-btn');
const chatMessages = document.getElementById('chat-messages');
const multipleChoiceInput = document.getElementById('multiple-choice-input');
const openTextInput = document.getElementById('open-text-input');
const loadingState = document.getElementById('loading-state');
const progressBar = document.getElementById('progress-bar');
const currentQuestionNum = document.getElementById('current-question-num');
const userTextInput = document.getElementById('user-text-input');
const submitTextBtn = document.getElementById('submit-text-btn');

// Start Conversation
startBtn.addEventListener('click', async () => {
    // Disable button to prevent double clicks
    startBtn.disabled = true;
    startBtn.textContent = 'Bezig met starten...';
    
    try {
        const response = await fetch('/ajax/politiek-gesprek.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'start_conversation',
                mode: 'adaptive'
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            conversationState.sessionId = data.session_id;
            conversationState.currentQuestion = data.question_number;
            
            welcomeScreen.classList.add('hidden');
            chatInterface.classList.remove('hidden');
            
            displayQuestion(data.question);
            updateProgress();
        } else {
            if (data.rate_limited) {
                alert('Je kunt maar één gesprek per uur starten. Probeer het later opnieuw.');
            } else {
                alert(data.error || 'Er is een fout opgetreden bij het starten van het gesprek');
            }
            startBtn.disabled = false;
            startBtn.textContent = 'Start het Gesprek';
        }
    } catch (error) {
        console.error('Error starting conversation:', error);
        alert('Er is een verbindingsfout opgetreden. Controleer je internetverbinding en probeer het opnieuw.');
        startBtn.disabled = false;
        startBtn.textContent = 'Start het Gesprek';
    }
});

// Display Question
function displayQuestion(questionData) {
    // Add AI message with intro
    addMessage('ai', questionData.intro);
    
    // Add AI message with actual question
    setTimeout(() => {
        addMessage('ai', questionData.question);
        
        // Show appropriate input type
        setTimeout(() => {
            if (questionData.mode === 'multiple_choice') {
                showMultipleChoice(questionData.options, questionData);
            } else {
                showOpenText(questionData);
            }
        }, 500);
    }, 800);
}

// Add Message to Chat
function addMessage(sender, content) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'} message-bubble`;
    
    const bubbleDiv = document.createElement('div');
    bubbleDiv.className = `max-w-md px-4 py-3 rounded-lg ${
        sender === 'user' 
            ? 'bg-primary text-white' 
            : 'bg-white border border-gray-200 text-gray-800'
    }`;
    bubbleDiv.innerHTML = content;
    
    messageDiv.appendChild(bubbleDiv);
    chatMessages.appendChild(messageDiv);
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Show Multiple Choice
function showMultipleChoice(options, questionData) {
    hideAllInputs();
    multipleChoiceInput.classList.remove('hidden');
    
    const choiceButtons = document.getElementById('choice-buttons');
    choiceButtons.innerHTML = '';
    
    options.forEach(option => {
        const button = document.createElement('button');
        button.className = 'bg-white border-2 border-gray-300 hover:border-primary hover:bg-primary/5 text-gray-800 px-6 py-3 rounded-lg font-medium transition-all';
        button.textContent = option;
        button.onclick = () => handleAnswer(option, questionData);
        choiceButtons.appendChild(button);
    });
}

// Show Open Text
function showOpenText(questionData) {
    hideAllInputs();
    openTextInput.classList.remove('hidden');
    userTextInput.value = '';
    userTextInput.focus();
    
    submitTextBtn.onclick = () => {
        const answer = userTextInput.value.trim();
        if (answer) {
            handleAnswer(answer, questionData);
        }
    };
    
    // Allow Enter to submit (Shift+Enter for new line)
    userTextInput.onkeydown = (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            submitTextBtn.click();
        }
    };
}

// Hide All Inputs
function hideAllInputs() {
    multipleChoiceInput.classList.add('hidden');
    openTextInput.classList.add('hidden');
    loadingState.classList.add('hidden');
}

// Handle Answer
async function handleAnswer(answer, questionData) {
    // Add user message
    addMessage('user', answer);
    
    // Show loading
    hideAllInputs();
    loadingState.classList.remove('hidden');
    
    try {
        const response = await fetch('/ajax/politiek-gesprek.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'submit_answer',
                session_id: conversationState.sessionId,
                answer: answer,
                question_data: questionData
            })
        });
        
        const data = await response.json();
        
        loadingState.classList.add('hidden');
        
        if (data.success) {
            if (data.completed) {
                // Show results
                await loadResults();
            } else {
                // Next question
                conversationState.currentQuestion = data.question_number;
                updateProgress();
                displayQuestion(data.question);
            }
        } else {
            alert(data.error || 'Er is een fout opgetreden');
        }
    } catch (error) {
        console.error('Error:', error);
        loadingState.classList.add('hidden');
        alert('Er is een fout opgetreden. Probeer het opnieuw.');
    }
}

// Update Progress
function updateProgress() {
    const percentage = (conversationState.currentQuestion / conversationState.totalQuestions) * 100;
    progressBar.style.width = percentage + '%';
    currentQuestionNum.textContent = conversationState.currentQuestion;
}

// Load Results
async function loadResults() {
    try {
        const response = await fetch('/ajax/politiek-gesprek.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'complete_conversation',
                session_id: conversationState.sessionId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            chatInterface.classList.add('hidden');
            resultsScreen.classList.remove('hidden');
            displayResults(data.results);
        } else {
            alert(data.error || 'Er is een fout opgetreden bij het laden van de resultaten');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Er is een fout opgetreden. Probeer het opnieuw.');
    }
}

// Display Results
function displayResults(results) {
    const resultsContent = document.getElementById('results-content');
    
    let html = `
        <!-- Top Match -->
        <div class="bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl p-6 mb-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Jouw beste match</h3>
            <div class="text-4xl font-bold text-primary mb-2">${results.top_party.name}</div>
            <div class="text-2xl font-semibold text-secondary">${results.top_party.percentage}% overeenkomst</div>
        </div>
        
        <!-- All Matches -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Top 5 Partijen</h3>
            <div class="space-y-3">
    `;
    
    results.all_matches.forEach((match, index) => {
        html += `
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-semibold text-gray-800">${index + 1}. ${match.name}</span>
                    <span class="font-bold text-primary">${match.percentage}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-primary to-secondary h-2 rounded-full" style="width: ${match.percentage}%"></div>
                </div>
            </div>
        `;
    });
    
    html += `
            </div>
        </div>
        
        <!-- AI Analysis -->
        <div class="prose max-w-none">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Jouw Politieke Analyse</h3>
            <div class="text-gray-700 leading-relaxed">
                ${results.ai_analysis}
            </div>
        </div>
        
        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/partijen" class="bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg font-semibold text-center transition-colors">
                Bekijk Partijen
            </a>
            <button onclick="window.location.reload()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition-colors">
                Opnieuw Doen
            </button>
        </div>
    `;
    
    resultsContent.innerHTML = html;
}
</script>

<?php require_once __DIR__ . '/templates/footer.php'; ?>

