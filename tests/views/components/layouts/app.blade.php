<!DOCTYPE html>
<html>
<head><title>{{ $title ?? 'Test' }}</title></head>
<body>
@livewireScripts
@fluxScripts
{{ $slot }}
</body>
</html>
