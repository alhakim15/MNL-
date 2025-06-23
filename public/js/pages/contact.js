  setTimeout(() => {
    const errorMsg = document.getElementById('error-message');
    if (errorMsg) {
      errorMsg.style.display = 'none';
    }

    const successMsg = document.getElementById('success-message');
    if (successMsg) {
      successMsg.style.display = 'none';
    }
  }, 3000); // 3000ms = 3 detik