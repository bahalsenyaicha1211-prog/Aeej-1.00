<div class="admPanel admPanel--full">
    <div class="admPanel__body" style="padding: 0;">
        <div class="table-wrap">
            <table class="table table--compact-rows" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.02); text-align: left;">
                        <th style="color: #64748b; font-size: 11px; text-transform: uppercase; width: 80px;">ID</th>
                        <th style="color: #64748b; font-size: 11px; text-transform: uppercase;">Nom du département</th>
                        <th style="color: #64748b; font-size: 11px; text-transform: uppercase; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody class="admRows">
                    @forelse($departements as $d)
                    <tr class="admRow" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td>
                            <span style="color: #4ade80; font-family: monospace; font-weight: 700; background: rgba(74, 222, 128, 0.1); padding: 4px 8px; border-radius: 6px;">
                                #{{ $d->iddep }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 800; color: #fff; font-size: 15px;">{{ $d->nom }}</div>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a class="admQuick__btn" href="{{ route('admin.departements.edit', $d) }}" style="padding: 6px 12px; font-size: 12px;">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('admin.departements.destroy', $d) }}" onsubmit="return confirm('Supprimer ce département ?');">
                                    @csrf @method('DELETE')
                                    <button class="admQuick__btn" style="border-color: rgba(239,68,68,0.3); color: #f87171; background: rgba(239,68,68,0.05); padding: 6px 12px; font-size: 12px;">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="padding: 60px; text-align: center; color: #64748b;">
                            <div style="font-size: 40px; margin-bottom: 10px;">📂</div>
                            {{ $q !== '' ? 'Aucun département ne correspond à « '.$q.' ».' : 'Aucun département enregistré pour le moment.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top: 20px;">
    {{ $departements->links('vendor.pagination.admin') ?? '' }}
</div>
