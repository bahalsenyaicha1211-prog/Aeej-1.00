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

<section class="profile-photo-section">
    <div class="photo-card">
        {{-- Formulaire d'upload automatique --}}
        <form id="autoUploadForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="avatar-container">
                <label for="photoInput" class="avatar-label" title="Cliquer pour changer de photo">
                    {{-- LOGIQUE D'AFFICHAGE CORRIGÉE --}}
                    @php
                        $path = auth()->user()->profile_photo_path;
                        $src = $path 
                               ? (str_starts_with($path, 'http') ? $path : asset('storage/' . $path)) 
                               : asset('images/default-avatar.png');
                    @endphp
                    
                    <img id="avatarPreview" src="{{ $src }}" alt="Avatar" class="avatar-img">
                    
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
            <p class="text-sm text-gray-500">Cliquez sur l'image pour la modifier.</p>
            
            {{-- Formulaire de suppression séparé --}}
            @if(auth()->user()->profile_photo_path)
                <form action="{{ route('profile.photo.update') }}" method="POST" onsubmit="return confirm('Supprimer la photo ?')">
                    @csrf 
                    @method('PATCH')
                    <input type="hidden" name="remove_photo" value="1">
                    <button type="submit" class="btn-remove">Supprimer la photo</button>
                </form>
            @endif
        </div>
    </div>
</section>

<script>
    document.getElementById('photoInput').onchange = function() {
        if (this.files && this.files[0]) {
            const overlay = document.getElementById('uploadOverlay');
            overlay.classList.add('is-active'); 
            document.getElementById('autoUploadForm').submit();
        }
    };
</script>