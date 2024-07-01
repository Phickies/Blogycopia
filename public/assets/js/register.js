function toggleSocialRegister() {
    const normalRegister = document.querySelector('.normal-register');
    const socialRegister = document.querySelector('.social-register');
    const loginLink = document.querySelector('.login-link');
    const toggleButton = document.querySelector('.toggle-button');

    if (socialRegister.classList.contains('slide-out')) {
        normalRegister.style.display = 'none';
        loginLink.style.display = 'none';
        toggleButton.style.display = 'none';

        socialRegister.classList.remove('slide-out');
        socialRegister.classList.add('slide-in');
    } else {
        normalRegister.style.display = 'block';
        loginLink.style.display = 'block';
        toggleButton.style.display = 'block';

        socialRegister.classList.remove('slide-in');
        socialRegister.classList.add('slide-out');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const registerButton = document.getElementById('registerButton');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    const usernameError = document.getElementById('usernameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    let formSubmitted = false;

    const validateInputs = () => {
        let isValid = true;

        if (!usernameInput.value.trim()) {
            if (formSubmitted) usernameError.style.display = 'block';
            isValid = false;
        } else {
            usernameError.style.display = 'none';
        }

        if (!emailInput.value.trim()) {
            if (formSubmitted) emailError.style.display = 'block';
            isValid = false;
        } else {
            emailError.style.display = 'none';
        }

        if (!passwordInput.value.trim()) {
            if (formSubmitted) passwordError.style.display = 'block';
            isValid = false;
        } else {
            passwordError.style.display = 'none';
        }

        if (passwordInput.value.trim() !== confirmPasswordInput.value.trim()) {
            if (formSubmitted) confirmPasswordError.style.display = 'block';
            isValid = false;
        } else {
            confirmPasswordError.style.display = 'none';
        }

        return isValid;
    };

    usernameInput.addEventListener('input', validateInputs);
    emailInput.addEventListener('input', validateInputs);
    passwordInput.addEventListener('input', validateInputs);
    confirmPasswordInput.addEventListener('input', validateInputs);

    registerButton.addEventListener('click', (event) => {
        formSubmitted = true;
        const isValid = validateInputs();
        if (!isValid) {
            event.preventDefault();
        }
    });
});