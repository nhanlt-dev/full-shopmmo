function togglePasswordVisibility(inputId, icon) {
    const input = document.getElementById(inputId);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
function expireOtpIfNeeded() {
    if (isset($_SESSION['register']['otp_created_at'])) {
        if (time() - $_SESSION['register']['otp_created_at'] > 300) {
            unset($_SESSION['register']['otp']);
            unset($_SESSION['register']['otp_created_at']);
        }
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.substring(1);
    
    if (hash && hash.startsWith('modal')) {
        const [modalId, passwordPart] = hash.split('%7C');
        const modalElement = document.getElementById(modalId);
        
        if (modalElement) {
            const myModal = new bootstrap.Modal(modalElement);
            myModal.show();

            if (passwordPart) {
                const [key, value] = passwordPart.split('=');
                if (key === 'password') {
                    const input = document.getElementById('passLogin');
                    if (input) {
                        input.value = decodeURIComponent(value);
                    }
                }
            }
        }
    }
});
