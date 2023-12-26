// JavaScript code for fetching and displaying blog posts
document.addEventListener('DOMContentLoaded', function () {
    // Fetch and display blog posts
    fetch('get_posts.php')
        .then(response => response.json())
        .then(posts => {
            const postsContainer = document.getElementById('posts-container');

            posts.forEach(post => {
                const postElement = document.createElement('div');
                postElement.classList.add('post');

                const titleElement = document.createElement('h3');
                titleElement.textContent = post.title;

                const contentElement = document.createElement('p');
                contentElement.textContent = post.content;

                postElement.appendChild(titleElement);
                postElement.appendChild(contentElement);

                postsContainer.appendChild(postElement);
            });
        });
});
