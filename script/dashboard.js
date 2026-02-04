document.addEventListener('DOMContentLoaded', function() {
    fetch('../config/get_profile_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            // 1. Update Profile Pictures (All instances: nav, dropdown, main card)
            const profileImages = document.querySelectorAll('.profile-img-display');
            profileImages.forEach(img => {
                // Add a timestamp to bypass browser caching when image updates
                img.src = data.profile_picture ? `${data.profile_picture}?t=${new Date().getTime()}` : '../img/default_profile_picture.jpg';
            });

            // 2. Update Name (All instances)
            const nameSpans = document.querySelectorAll('.user-name-display');
            nameSpans.forEach(span => {
                span.textContent = data.firstname;
            });

            // 3. Update Text Content Fields
            // Helper function to safely update text
            const setText = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value || "Not set";
            };

            setText('email', data.email);
            setText('contact', data.contact);
            setText('bio', data.bio);
            setText('age', data.age);
            setText('date_of_birth', data.date_of_birth);
            setText('gender', data.gender);
            setText('marital_status', data.marital_status);
            setText('address', data.address);
        })
        .catch(error => console.error('Error fetching profile data:', error));
});