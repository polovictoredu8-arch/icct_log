document.addEventListener('DOMContentLoaded', function() {
    // 1. Fetch current data to pre-fill the form
    fetch('../config/get_profile_data.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
                return;
            }

            // Helper to safe set value
            const setVal = (id, value) => {
                const el = document.getElementById(id);
                if (el && value) el.value = value;
            };

            setVal('email', data.email);
            setVal('address', data.address);
            setVal('gender', data.gender);
            setVal('age', data.age);
            setVal('dob', data.date_of_birth);
            setVal('contact', data.contact);
            setVal('marital', data.marital_status);
            setVal('bio', data.bio);
            
            // Update Header Profile Images even on this page
            const profileImages = document.querySelectorAll('.profile-img-display');
            profileImages.forEach(img => {
                img.src = data.profile_picture ? `${data.profile_picture}?t=${new Date().getTime()}` : '../img/default_profile_picture.jpg';
            });
            
            const nameSpans = document.querySelectorAll('.user-name-display');
            nameSpans.forEach(span => {
                span.textContent = data.firstname;
            });
        })
        .catch(error => console.error('Error fetching data:', error));
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
        alert(data); // Show server response
        // Reload page to show new image and data
        location.reload(); 
    })
    .catch(error => console.error('Error updating profile:', error));
}

function handleDelete() {
    if(confirm("Are you sure you want to delete this account? This cannot be undone.")) {
        // You would need a delete_account.php script for this
        alert("Feature requires delete_account.php backend implementation.");
        // window.location.href = '../config/delete_account.php';
    }
}