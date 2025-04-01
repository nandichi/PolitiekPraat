document.addEventListener('DOMContentLoaded', function() {
    // Get newsletter form
    const newsletterForm = document.getElementById('newsletterForm');
    const messageDiv = document.getElementById('newsletterMessage');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get email input value
            const emailInput = document.getElementById('newsletter-email');
            const email = emailInput.value.trim();
            
            // Show loading state
            setMessage('Even geduld...', 'info');
            
            // Send AJAX request
            const formData = new FormData();
            formData.append('email', email);
            
            fetch('/newsletter/subscribe', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    setMessage(data.message, 'success');
                    emailInput.value = ''; // Clear input on success
                } else if (data.status === 'info') {
                    setMessage(data.message, 'info');
                } else {
                    setMessage(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                setMessage('Er is iets misgegaan. Probeer het later opnieuw.', 'error');
            });
        });
    }
    
    // Function to set message with appropriate styling
    function setMessage(message, type) {
        if (!messageDiv) return;
        
        // Remove all previous classes
        messageDiv.classList.remove('hidden', 'text-green-600', 'bg-green-100', 'text-red-600', 'bg-red-100', 'text-blue-600', 'bg-blue-100');
        messageDiv.classList.add('p-3', 'rounded-lg', 'border');
        
        // Add appropriate styling based on message type
        switch(type) {
            case 'success':
                messageDiv.classList.add('text-green-600', 'bg-green-100', 'border-green-200');
                break;
            case 'error':
                messageDiv.classList.add('text-red-600', 'bg-red-100', 'border-red-200');
                break;
            case 'info':
                messageDiv.classList.add('text-blue-600', 'bg-blue-100', 'border-blue-200');
                break;
        }
        
        // Set the message and show the div
        messageDiv.innerHTML = message;
        
        // If success, hide the message after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 5000);
        }
    }
}); 