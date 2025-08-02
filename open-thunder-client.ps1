# Open VS Code and Thunder Client
Write-Host "Opening VS Code..." -ForegroundColor Green
Start-Process code -ArgumentList "."

# Wait a moment for VS Code to open
Start-Sleep -Seconds 2

Write-Host "Thunder Client should now be accessible via:" -ForegroundColor Yellow
Write-Host "1. Press Ctrl+Shift+P" -ForegroundColor Cyan
Write-Host "2. Type 'Thunder Client'" -ForegroundColor Cyan
Write-Host "3. Select 'Thunder Client: New Request'" -ForegroundColor Cyan
Write-Host ""
Write-Host "Or use the Thunder Client icon in the sidebar" -ForegroundColor Cyan 