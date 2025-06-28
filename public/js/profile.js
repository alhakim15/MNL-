// Profile Page JavaScript

// Show success message if exists
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    
    if (success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: success,
            showConfirmButton: false,
            timer: 3000
        });
    }
});

// Toggle edit mode for biodata
function toggleEdit(section) {
    if (section === 'biodata') {
        const form = document.getElementById('biodata-form');
        const inputs = form.querySelectorAll('input:not([type="file"]), select');
        const actions = form.querySelector('.form-actions');
        const editBtn = document.querySelector('.edit-btn');
        
        // Toggle readonly/disabled state
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.disabled = !input.disabled;
            } else {
                input.readOnly = !input.readOnly;
            }
        });
        
        // Toggle form actions visibility
        if (actions.style.display === 'none' || actions.style.display === '') {
            actions.style.display = 'flex';
            editBtn.style.display = 'none';
        } else {
            actions.style.display = 'none';
            editBtn.style.display = 'flex';
        }
    }
}

// Cancel edit mode
function cancelEdit(section) {
    if (section === 'biodata') {
        const form = document.getElementById('biodata-form');
        const inputs = form.querySelectorAll('input:not([type="file"]), select');
        const actions = form.querySelector('.form-actions');
        const editBtn = document.querySelector('.edit-btn');
        
        // Reset form to original values
        form.reset();
        
        // Restore readonly/disabled state
        inputs.forEach(input => {
            if (input.tagName === 'SELECT') {
                input.disabled = true;
            } else {
                input.readOnly = true;
            }
        });
        
        // Hide form actions
        actions.style.display = 'none';
        editBtn.style.display = 'flex';
    }
}

// Preview uploaded image
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const photoContainer = document.querySelector('.profile-photo-container');
            const existingPhoto = photoContainer.querySelector('.profile-photo');
            const placeholder = photoContainer.querySelector('.profile-photo-placeholder');
            
            if (existingPhoto) {
                existingPhoto.src = e.target.result;
            } else if (placeholder) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'profile-photo';
                img.alt = 'Profile Photo';
                placeholder.replaceWith(img);
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Password Modal Functions
function showPasswordModal() {
    document.getElementById('passwordModal').style.display = 'block';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
    // Reset form
    document.querySelector('#passwordModal form').reset();
}

// Delete Account Modal Functions
function showDeleteModal() {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Tindakan ini akan menghapus akun Anda secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus akun!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteModal').style.display = 'block';
        }
    });
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    // Reset form
    document.querySelector('#deleteModal form').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const passwordModal = document.getElementById('passwordModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target === passwordModal) {
        closePasswordModal();
    }
    
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
}

// Form validation
document.querySelector('#passwordModal form').addEventListener('submit', function(e) {
    const password = this.querySelector('input[name="password"]').value;
    const confirmPassword = this.querySelector('input[name="password_confirmation"]').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Konfirmasi kata sandi tidak cocok!',
        });
    }
});

// Delete account form validation
document.querySelector('#deleteModal form').addEventListener('submit', function(e) {
    const password = this.querySelector('input[name="password"]').value;
    
    if (!password) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Kata sandi harus diisi untuk konfirmasi!',
        });
    }
});

// Show loading state for forms
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Memproses...';
        }
    });
});
