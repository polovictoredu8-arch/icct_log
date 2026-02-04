// Duplicate concise handlers removed; full handlers are defined further down.
        
 document.addEventListener('DOMContentLoaded', function() {
            // Fetch existing user data
            fetch('../config/get_profile_data.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                    } else {
                        // Populate the form fields with data
                        document.getElementById('email').value = data.email;
                        document.getElementById('address').value = data.address;
                        document.getElementById('gender').value = data.gender;
                        document.getElementById('age').value = data.age;
                        document.getElementById('date_of_birth').value = data.date_of_birth;
                        document.getElementById('contact').value = data.contact;
                        document.getElementById('marital_status').value = data.marital_status;
                        document.getElementById('bio').value = data.bio;
                        //profilePicture.src = data.profile_picture;
                    }
                })
                .catch(error => console.error('Error fetching profile data:', error));
        });

        function handleUpdate() {
            const form = document.getElementById('editAccountForm');
            const formData = new FormData(form);

            fetch('../config/update_profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data); // Show the response from the server
            })
            .catch(error => console.error('Error updating profile:', error));
        }

        function handleDelete() {
            if(confirm("Are you sure you want to delete this account?")) {
                alert("Account deleted.");
                window.location.href = '../index.html';
            }
        }