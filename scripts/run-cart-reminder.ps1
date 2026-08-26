$key = [Environment]::GetEnvironmentVariable('GAMEINA_REMINDER_KEY', 'User')
if (-not $key) { throw 'GAMEINA_REMINDER_KEY is not configured.' }
$appUrl = [Environment]::GetEnvironmentVariable('GAMEINA_BASE_URL', 'User')
if (-not $appUrl) { throw 'GAMEINA_BASE_URL is not configured.' }
Invoke-WebRequest -Uri "$($appUrl.TrimEnd('/'))/cart/remind_abandoned?key=$([Uri]::EscapeDataString($key))" -UseBasicParsing | Out-Null
