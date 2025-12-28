<?php
$showModal = true;
if (isset($_SESSION['userData']['id'])) {
    $userId = $_SESSION['userData']['id'];
    $resultStatusPopup = mysqli_query($link, "SELECT id FROM pages WHERE idRepresentativePersion = $userId");
    if (mysqli_num_rows($resultStatusPopup) > 0) {
        $showModal = false;
    }
} 
if ($showModal): ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const path = window.location.pathname;
    const regex = /^\/([a-zA-Z0-9_-]+)\/$/;
    if (regex.test(path)) {
        const myModal = new bootstrap.Modal(document.getElementById("modalPopup"));
        myModal.show(); // Mở modal
    }
});
</script>
<?php endif; ?>

