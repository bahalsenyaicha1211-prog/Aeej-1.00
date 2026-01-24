<x-membre-layout>
    <x-slot name="header">
        Notifications
    </x-slot>

    <div class="notifications">

        @forelse($notifications as $notification)

            @php
                $data = $notification->data;
                $isAnnonce = isset($data['annonce_id']);
            @endphp

            <div class="notification {{ $notification->read_at ? '' : 'is-unread' }}">

                <div class="notification__dot"></div>

                <div class="notification__content">
                    <div class="notification__header">
                        <span class="notification__title">
                            {{ $isAnnonce ? 'Nouvelle annonce' : 'Notification' }}
                        </span>

                        <span class="notification__date">
                            {{ $notification->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <p class="notification__text">
                        {{ $data['excerpt'] ?? 'Vous avez une nouvelle notification.' }}
                    </p>

                    @if($isAnnonce)
                        <a class="notification__link"
                           href="{{ route('membre.annonces.show', $data['annonce_id']) }}">
                            Voir l’annonce →
                        </a>
                    @endif
                </div>
            </div>

        @empty
            <div class="notification-empty">
                Aucune notification pour le moment.
            </div>
        @endforelse

        <div class="pagination">
            {{ $notifications->links() }}
        </div>
    </div>
</x-membre-layout>
