(function() {
    // 1. Prevent Back Button (History Manipulation)
    // This pushes the current state so if they click back, they stay here or get redirected
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
    };

    // 2. Check Session Status with Backend
    function checkSession() {
        fetch('../config/check_session.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'expired') {
                    alert("Your session has expired due to inactivity.");
                    window.location.href = '../index.html'; // Force Logout
                } else if (data.status === 'unauthorized') {
                    window.location.href = '../index.html'; // Force Logout
                }
                // If 'active', do nothing (token is effectively renewed by the PHP script)
            })
            .catch(error => {
                console.error('Session check failed:', error);
            });
    }

    // Run check immediately on load
    checkSession();

    // OPTIONAL: Run check every 1 minute to detect timeout while page is open but idle
    setInterval(checkSession, 60000);

})();