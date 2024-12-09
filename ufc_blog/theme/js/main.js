document.addEventListener('DOMContentLoaded', function() {
    // Like functionality
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            
            try {
                const response = await fetch('/posts/like.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `post_id=${postId}`
                });
                
                const data = await response.json();
                
                if (data.action === 'liked') {
                    this.classList.add('liked');
                } else {
                    this.classList.remove('liked');
                }
                
                this.querySelector('.like-count').textContent = data.count;
            } catch (error) {
                console.error('Error:', error);
            }
        });
    });

    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // Image preview
    const imageInput = document.querySelector('input[type="file"]');
    const imagePreview = document.querySelector('.image-preview');
    
    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});