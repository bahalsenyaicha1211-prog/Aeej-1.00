<section class="profile-photo-section">
    <div class="photo-card">
        <h2 class="photo-card__title">Photo de profil</h2>
        
        <form id="autoUploadForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="avatar-container">
                <label for="photoInput" class="avatar-label" title="Cliquer pour changer de photo">
                    <img id="avatarPreview" 
                         src="{{ auth()->user()->profile_photo_path ?? asset('images/default-avatar.png') }}" 
                         alt="Avatar" 
                         class="avatar-img">
                    
                    <div id="uploadOverlay" class="avatar-overlay">
                        <div class="spinner"></div>
                    </div>
                    
                    <div class="camera-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </div>
                </label>

                <input type="file" name="photo" id="photoInput" accept="image/*" style="display: none;">
            </div>
        </form>

        <div class="photo-info">
            <p class="photo-info__text">Cliquez sur l'image pour la modifier.</p>
            @if(auth()->user()->profile_photo_path)
                <form action="{{ route('profile.photo.update') }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" name="remove_photo" value="1" class="btn-remove">Supprimer la photo</button>
                </form>
            @endif
        </div>
    </div>
</section>

<script>
    // Déclenchement automatique de l'envoi
    document.getElementById('photoInput').onchange = function() {
        const overlay = document.getElementById('uploadOverlay');
        overlay.classList.add('is-active'); // Affiche le spinner
        document.getElementById('autoUploadForm').submit(); // Envoie le formulaire
    };
</script>