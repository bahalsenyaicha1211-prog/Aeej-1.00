<style>
    .avatar-container {
        position: relative;
        width: 140px; /* Un peu plus grand pour le style */
        height: 140px;
        margin: 0 auto 15px;
    }

    .avatar-label {
        cursor: pointer;
        display: block;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        border: 4px solid #fff; /* Bordure blanche pour détacher l'image */
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .avatar-label:hover {
        transform: scale(1.02);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .camera-icon {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .avatar-label:hover .camera-icon { opacity: 1; }
    .camera-icon svg { width: 32px; }

    .avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .avatar-overlay.is-active { display: flex; }

    .spinner {
        width: 35px;
        height: 35px;
        border: 4px solid #3182ce;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .photo-info { text-align: center; }

    .btn-remove {
        background: none;
        border: none;
        color: #e53e3e;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
        margin-top: 8px;
        padding: 5px;
    }
    
    .btn-remove:hover { color: #c53030; }
</style>

<!-- section class="profile-photo-section">
    <div class="photo-card">
        {{-- Le formulaire reste, au cas où tu voudrais remettre l'upload plus tard --}}
        <form id="autoUploadForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="avatar-container">
                {{-- On garde le label cliquable, mais on change le contenu --}}
                <label for="photoInput" class="avatar-label" title="Cliquer pour changer de photo (désactivé)">
                    
                    {{-- NOUVELLE LOGIQUE D'AFFICHAGE --}}
                    @if($user->profile_photo_path)
                        {{-- Si une photo existe (anciennes données), on l'affiche --}}
                        <img id="avatarPreview" src="{{ $user->profile_photo_path }}" alt="Avatar" class="avatar-img">
                    @else
                        {{-- SINON, ON AFFICHE LES INITIALES --}}
                        <div class="avatar-initials">
                            {{ $initials }}
                        </div>
                    @endif
                    
                    <div id="uploadOverlay" class="avatar-overlay">
                        <div class="spinner"></div>
                    </div>
                  
                    {{-- Icône caméra masquée pour l'instant car l'upload est abandonné --}}
                    {{-- <div class="camera-icon">...</div> --}}
                </label>

                {{-- On désactive l'input file pour l'instant --}}
                <input type="file" name="photo" id="photoInput" accept="image/*" style="display: none;" disabled>
            </div>
        </form>

        <div class="photo-info">
            <p class="text-sm text-gray-500">Avatar généré à partir de votre nom.</p>
        </div>
    </div>
</section>
-->
<script>
    document.getElementById('photoInput').onchange = function() {
        if (this.files && this.files[0]) {
            const overlay = document.getElementById('uploadOverlay');
            overlay.classList.add('is-active'); 
            document.getElementById('autoUploadForm').submit();
        }
    };
</script>