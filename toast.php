<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<div id="toast-container"></div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let message = "";
        let type = "";
        
        <?php if (isset($_SESSION['error'])): ?>
            message = "<?php echo $_SESSION['error']; ?>";
            type = "error";
            <?php unset($_SESSION['error']); ?>
        <?php elseif (isset($_SESSION['success'])): ?>
            message = "<?php echo $_SESSION['success']; ?>";
            type = "success";
            <?php unset($_SESSION['success']); ?>
        <?php elseif (isset($_SESSION['info'])): ?>
            message = "<?php echo $_SESSION['info']; ?>";
            type = "info";
            <?php unset($_SESSION['info']); ?>
        <?php elseif (isset($_SESSION['warning'])): ?>
            message = "<?php echo $_SESSION['warning']; ?>";
            type = "warning";
            <?php unset($_SESSION['warning']); ?>
        <?php elseif (isset($_SESSION['notice'])): ?>
            message = "<?php echo $_SESSION['notice']; ?>";
            type = "notice";
            <?php unset($_SESSION['notice']); ?>
        <?php endif; ?>
        
        if (message) {
            showToast(message, type);
        }
    });
    
    function showToast(message, type) {
        let toastContainer = document.getElementById("toast-container");
        let toastMessage = document.createElement("div");
        toastMessage.className = "toast-message " + type;
        
        let closeButton = document.createElement("span");
        closeButton.innerHTML = "&times;";
        closeButton.className = "toast-close";
        closeButton.onclick = function() {
            toastMessage.remove();
        };
        
        let progressBar = document.createElement("div");
        progressBar.className = "toast-progress";
        
        toastMessage.innerText = message;
        toastMessage.appendChild(closeButton);
        toastMessage.appendChild(progressBar);
        toastContainer.appendChild(toastMessage);
        
        let duration = 2000;
        setTimeout(() => {
            toastMessage.remove();
        }, duration);
        
        progressBar.style.animation = `progressBarAnimation ${duration}ms linear`;
    }
</script>

<style>
    #toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
    .toast-message {
        position: relative;
        padding: 15px;
        margin-bottom: 10px;
        color: #fff;
        border-radius: 5px;
        opacity: 0.9;
        min-width: 300px;
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        overflow: hidden;
    }
    .toast-close {
        margin-left: 15px;
        cursor: pointer;
        font-weight: bold;
        font-size: 20px;
    }
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.5);
    }
    @keyframes progressBarAnimation {
        from { width: 100%; }
        to { width: 0%; }
    }
    .toast-message.success { background: green; }
    .toast-message.error { background: red; }
    .toast-message.info { background: blue; }
    .toast-message.warning { background: orange; }
    .toast-message.notice { background: purple; }
</style>