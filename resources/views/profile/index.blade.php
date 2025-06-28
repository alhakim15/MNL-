<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pribadi - Mutiara Nasional Line</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poiret+One&display=swap"
        rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Navbar Section -->
    @include('components.navbar')

    <div class="profile-container">
        <div class="profile-header">
            <div class="back-button">
                <a href="{{ route('home') }}" class="back-link">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <h1 class="profile-title">Profil Pribadi</h1>
        </div>

        <!-- Profile Photo Section -->
        <div class="profile-photo-section">
            <div class="profile-photo-container">
                @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="profile-photo">
                @else
                <div class="profile-photo-placeholder">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                @endif
                <button class="photo-camera-btn" onclick="document.getElementById('profile_photo').click();">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23 19C23 19.5304 22.7893 20.0391 22.4142 20.4142C22.0391 20.7893 21.5304 21 21 21H3C2.46957 21 1.96086 20.7893 1.58579 20.4142C1.21071 20.0391 1 19.5304 1 19V8C1 7.46957 1.21071 6.96086 1.58579 6.58579C1.96086 6.21071 2.46957 6 3 6H7L9 3H15L17 6H21C21.5304 6 22.0391 6.21071 22.4142 6.58579C22.7893 6.96086 23 7.46957 23 8V19Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="12" cy="13" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Biodata Section -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Biodata</h2>
                <button class="edit-btn" onclick="toggleEdit('biodata')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M18.5 2.50023C18.8978 2.1024 19.4374 1.87891 20 1.87891C20.5626 1.87891 21.1022 2.1024 21.5 2.50023C21.8978 2.89805 22.1213 3.43762 22.1213 4.00023C22.1213 4.56284 21.8978 5.1024 21.5 5.50023L12 15.0002L8 16.0002L9 12.0002L18.5 2.50023Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <form id="biodata-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="profile-form">
                @csrf
                @method('PUT')

                <input type="file" id="profile_photo" name="profile_photo" accept="image/*" style="display: none;"
                    onchange="previewImage(this)">

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Depan</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Belakang</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" readonly>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="date_of_birth"
                            value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" readonly>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="gender" disabled>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : ''
                                }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : ''
                                }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" readonly>
                    </div>
                </div>

                <div class="form-actions" style="display: none;">
                    <button type="submit" class="save-btn">Simpan Perubahan</button>
                    <button type="button" class="cancel-btn" onclick="cancelEdit('biodata')">Batal</button>
                </div>
            </form>
        </div>

        <!-- Control Account Section -->
        <div class="profile-section">
            <div class="section-header">
                <h2>Kontrol Akun</h2>
            </div>

            <div class="control-options">
                <button class="control-btn change-password-btn" onclick="showPasswordModal()">
                    <span>Ubah Kata Sandi</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <button class="control-btn delete-account-btn" onclick="showDeleteModal()">
                    <span>Hapus Akun</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ubah Kata Sandi</h3>
                <button class="close-modal" onclick="closePasswordModal()">&times;</button>
            </div>
            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label>Kata Sandi Baru</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="save-btn">Ubah Kata Sandi</button>
                    <button type="button" class="cancel-btn" onclick="closePasswordModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Hapus Akun</h3>
                <button class="close-modal" onclick="closeDeleteModal()">&times;</button>
            </div>
            <p>Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.</p>
            <form action="{{ route('profile.delete') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="form-group">
                    <label>Masukkan kata sandi untuk konfirmasi</label>
                    <input type="password" name="password" required>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="delete-btn">Hapus Akun</button>
                    <button type="button" class="cancel-btn" onclick="closeDeleteModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/profile.js') }}"></script>
</body>

</html>