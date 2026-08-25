$key = [Environment]::GetEnvironmentVariable('GAMEINA_REMINDER_KEY', 'User')
if (-not $key) { throw 'GAMEINA_REMINDER_KEY is not configured.' }
Invoke-WebRequest -Uri "http://127.0.0.1:8000/cart/remind_abandoned?key=$([Uri]::EscapeDataString($key))" -UseBasicParsing | Out-Null
