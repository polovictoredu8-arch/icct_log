document.addEventListener('DOMContentLoaded', function() {
    // Function to get the value of a query parameter
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    const firstName = getQueryParam('firstname');

    if (firstName) {
        document.getElementById('name').textContent = firstName;
    } else {
        document.getElementById('name').textContent = 'User';
    }

    // Fetch profile data from PHP script
    fetch('../config/get_profile_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
            } else {
                // Populate the placeholders with data
                document.getElementById('name').textContent = data.firstname;
                document.getElementById('email').textContent = data.email;
                document.getElementById('contact').textContent = data.contact;
                document.getElementById('bio').textContent = data.bio;
                document.getElementById('age').textContent = data.age;
                document.getElementById('date_of_birth').textContent = data.date_of_birth;
                document.getElementById('gender').textContent = data.gender;
                document.getElementById('marital_status').textContent = data.marital_status;
                document.getElementById('address').textContent = data.address;

                // Handle profile picture
                const profilePicture = document.getElementById('profile-picture');
                if (data.profile_picture) {
                    profilePicture.src = data.profile_picture;
                } else {
                    profilePicture.src = '../img/default_profile_picture.jpg'; // Use a default image
                }
            }
        })
        .catch(error => console.error('Error fetching profile data:', error));
});