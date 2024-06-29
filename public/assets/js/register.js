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