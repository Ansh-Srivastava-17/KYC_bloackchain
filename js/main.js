document.addEventListener('DOMContentLoaded', () => {
    const userLoginBtn = document.getElementById('userLoginBtn');
    const orgLoginBtn = document.getElementById('orgLoginBtn');
    const userLoginForm = document.getElementById('userLoginForm');
    const userRegisterForm = document.getElementById('userRegisterForm');
    const orgLoginForm = document.getElementById('orgLoginForm');
    const orgRegisterForm = document.getElementById('orgRegisterForm');
    const userRegisterLink = document.getElementById('userRegisterLink');
    const orgRegisterLink = document.getElementById('orgRegisterLink');

    userLoginBtn.addEventListener('click', () => {
        userLoginForm.style.display = 'block';
        orgLoginForm.style.display = 'none';
        userRegisterForm.style.display = 'none';
        orgRegisterForm.style.display = 'none';
    });

    orgLoginBtn.addEventListener('click', () => {
        orgLoginForm.style.display = 'block';
        userLoginForm.style.display = 'none';
        userRegisterForm.style.display = 'none';
        orgRegisterForm.style.display = 'none';
    });

    userRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        userRegisterForm.style.display = 'block';
        userLoginForm.style.display = 'none';
    });

    orgRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        orgRegisterForm.style.display = 'block';
        orgLoginForm.style.display = 'none';
    });

    // Form submission handlers
    document.getElementById('userLogin').addEventListener('submit', handleUserLogin);
    document.getElementById('userRegister').addEventListener('submit', handleUserRegister);
    document.getElementById('orgLogin').addEventListener('submit', handleOrgLogin);
    document.getElementById('orgRegister').addEventListener('submit', handleOrgRegister);
});

function handleFetch(url, formData) {
    return fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Failed to parse JSON:', e);
            throw new Error('The server response was not valid JSON');
        }
    });
}

function handleUserLogin(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    handleFetch('php/login.php', formData)
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('An error occurred. Please check the console for more information.');
        });
}

function handleUserRegister(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    handleFetch('php/register_user.php', formData)
        .then(data => {
            if (data.success) {
                alert('Registration successful. Please login.');
                document.getElementById('userLoginForm').style.display = 'block';
                document.getElementById('userRegisterForm').style.display = 'none';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('An error occurred. Please check the console for more information.');
        });
}

function handleOrgLogin(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    handleFetch('php/login.php', formData)
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('An error occurred. Please check the console for more information.');
        });
}

function handleOrgRegister(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    handleFetch('php/register_org.php', formData)
        .then(data => {
            if (data.success) {
                alert('Registration successful. Please wait for admin approval.');
                document.getElementById('orgLoginForm').style.display = 'block';
                document.getElementById('orgRegisterForm').style.display = 'none';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert('An error occurred. Please check the console for more information.');
        });
}