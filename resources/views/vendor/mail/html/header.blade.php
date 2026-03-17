@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">

<img src="{{ $message->embed(public_path('storage/photo/logo.jpeg')) }}" class="logo" alt="Logo">

</a>
</td>
</tr>
