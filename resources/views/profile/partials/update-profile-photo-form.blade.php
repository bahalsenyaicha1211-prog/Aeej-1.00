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

<div class="flex flex-col items-center p-4">
    {{-- Formulaire unique pour Upload --}}
    <form id="avatarForm" action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf
        @method('PATCH')
        
        <label for="photoInput" class="group relative block w-32 h-32 cursor-pointer">
            {{-- L'image de l'avatar avec détection URL Cloudinary --}}
            <img id="preview" 
                 src="{{ $user->profile_photo_path ? (str_starts_with($user->profile_photo_path, 'http') ? $user->profile_photo_path : asset('storage/'.$user->profile_photo_path)) : asset('images/default-avatar.png') }}" 
                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg group-hover:opacity-75 transition">
            
            {{-- Overlay Caméra au survol --}}
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                <span class="bg-black bg-opacity-50 text-white p-2 rounded-full">📷</span>
            </div>

            {{-- Spinner caché --}}
            <div id="loading" class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 rounded-full">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </label>

        <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="uploadAvatar()">
    </form>

    {{-- Bouton Supprimer (uniquement si une photo existe) --}}
    @if($user->profile_photo_path)
        <form action="{{ route('profile.photo.update') }}" method="POST" class="mt-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="remove_photo" value="1">
            <button type="submit" class="text-red-500 text-sm hover:underline">
                Supprimer la photo
            </button>
        </form>
    @endif
</div>

<script>
function uploadAvatar() {
    const input = document.getElementById('photoInput');
    if (input.files.length > 0) {
        document.getElementById('loading').classList.remove('hidden');
        document.getElementById('avatarForm').submit();
    }
}
</script>