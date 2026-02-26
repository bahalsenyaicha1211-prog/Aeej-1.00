<style>
    .avatar-upload-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem 0;
    }

    /* Le cercle cliquable unique */
    .avatar-clickable {
        position: relative;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        cursor: pointer;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .avatar-clickable:hover {
        transform: scale(1.03);
    }

    .avatar-clickable img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Overlay au survol avec icône caméra */
    .avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        color: white;
        font-size: 1.5rem;
    }

    .avatar-clickable:hover .avatar-overlay {
        opacity: 1;
    }

    /* Spinner de chargement (masqué par défaut) */
    .loading-spinner {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 10;
    }

    .loading-spinner.active { display: flex; }

    .loader {
        width: 40px;
        height: 40px;
        border: 4px solid #3182ce;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .btn-delete {
        margin-top: 1rem;
        color: #e53e3e;
        font-size: 0.875rem;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: underline;
    }
</style>

<div class="avatar-upload-container">
    {{-- Formulaire d'upload automatique --}}
    <form id="avatarAutoForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        
        <label for="photoInput" class="avatar-clickable" title="Cliquez pour changer de photo">
            {{-- Image --}}
            <img src="{{ auth()->user()->profile_photo_path 
                ? (str_starts_with(auth()->user()->profile_photo_path, 'http') ? auth()->user()->profile_photo_path : asset('storage/'.auth()->user()->profile_photo_path)) 
                : asset('images/default-avatar.png') }}" alt="Avatar">
            
            {{-- Overlay Caméra --}}
            <div class="avatar-overlay">
                <span>📷</span>
            </div>

            {{-- Spinner --}}
            <div id="uploadSpinner" class="loading-spinner">
                <div class="loader"></div>
            </div>
        </label>

        {{-- Input invisible --}}
        <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" style="display:none" onchange="submitAvatar()">
    </form>

    {{-- Bouton supprimer --}}
    @if(auth()->user()->profile_photo_path)
        <form action="{{ route('profile.photo.update') }}" method="POST" onsubmit="return confirm('Supprimer cette photo ?')">
            @csrf
            @method('PATCH')
            <input type="hidden" name="remove_photo" value="1">
            <button type="submit" class="btn-delete">Retirer la photo</button>
        </form>
    @endif
</div>

<script>
    function submitAvatar() {
        const input = document.getElementById('photoInput');
        if (input.files.length > 0) {
            document.getElementById('uploadSpinner').classList.add('active');
            document.getElementById('avatarAutoForm').submit();
        }
    }
</script>