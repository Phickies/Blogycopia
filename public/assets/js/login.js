function toggleSocialLogin() {
    const normalLogin = document.querySelector('.normal-login');
    const socialLogin = document.querySelector('.social-login');
    const forgotPassword = document.querySelector('.forgot-password');
    const registerLink = document.querySelector('.register-link');
    const toggleButton = document.querySelector('.toggle-button');

    if (socialLogin.classList.contains('slide-out')) {
        normalLogin.style.display = 'none';
        forgotPassword.style.display = 'none';
        registerLink.style.display = 'none';
        toggleButton.style.display = 'none';

        socialLogin.classList.remove('slide-out');
        socialLogin.classList.add('slide-in');
    } else {
        normalLogin.style.display = 'block';
        forgotPassword.style.display = 'block';
        registerLink.style.display = 'block';
        toggleButton.style.display = 'block';

        socialLogin.classList.remove('slide-in');
        socialLogin.classList.add('slide-out');
    }
}