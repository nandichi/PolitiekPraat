/**
 * Blog Likes Functionality
 * Handles like/unlike actions on blog posts
 */

(function() {
    'use strict';
    
    // Initialize blog likes functionality when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initBlogLikes();
    });
    
    function initBlogLikes() {
        // Find all like buttons on the page
        const likeButtons = document.querySelectorAll('[data-like-button]');
        
        likeButtons.forEach(function(button) {
            button.addEventListener('click', handleLikeClick);
        });
    }
    
    function handleLikeClick(event) {
        event.preventDefault();
        
        const button = event.currentTarget;
        const blogSlug = button.dataset.blogSlug;
        const action = button.dataset.liked === 'true' ? 'unlike' : 'like';
        
        if (!blogSlug) {
            console.warn('Blog slug not found on like button');
            return;
        }
        
        // Send like/unlike request
        fetch(`/blogs/${blogSlug}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button state
                button.dataset.liked = action === 'like' ? 'true' : 'false';
                
                // Update like count if element exists
                const countElement = button.querySelector('[data-like-count]');
                if (countElement && typeof data.likes !== 'undefined') {
                    countElement.textContent = data.likes;
                }
                
                // Toggle visual state
                button.classList.toggle('liked', action === 'like');
            }
        })
        .catch(error => {
            console.error('Error updating like:', error);
        });
    }
})();

