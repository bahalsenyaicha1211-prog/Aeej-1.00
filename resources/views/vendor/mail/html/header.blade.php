@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://aeej-sd58.onrender.com/images/drapeau/AEEJ.png" style="width: 80px;" alt="AEEJ">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
