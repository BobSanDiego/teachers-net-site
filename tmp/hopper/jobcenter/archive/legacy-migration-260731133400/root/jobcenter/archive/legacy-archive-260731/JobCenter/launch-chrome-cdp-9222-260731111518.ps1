[CmdletBinding()]
param(
    [string]$Url = 'http://127.0.0.1:8768/#wizard-authority-v1',
    [int]$Port = 9222,
    [int]$TimeoutSeconds = 20
)

$ErrorActionPreference = 'Stop'
$profilePath = 'C:\Main\Active\Projects\Teachers.Net\tmp\chrome-qa-profile'
$endpoint = "http://127.0.0.1:$Port/json/version"
$chromeCandidates = @(
    "$env:ProgramFiles\Google\Chrome\Application\chrome.exe",
    "$env:ProgramFiles(x86)\Google\Chrome\Application\chrome.exe",
    "$env:LocalAppData\Google\Chrome\Application\chrome.exe"
)
$chromePath = $chromeCandidates | Where-Object { Test-Path -LiteralPath $_ } | Select-Object -First 1

function Get-ChromeEndpoint {
    try {
        $response = Invoke-RestMethod -Uri $endpoint -TimeoutSec 2
        if ($response.Browser -match 'Chrome') {
            return $response
        }
    } catch {
        return $null
    }
    return $null
}

$healthy = Get-ChromeEndpoint
if (-not $healthy) {
    if (-not $chromePath) {
        throw 'Google Chrome executable was not found in the standard Windows installation paths.'
    }

    New-Item -ItemType Directory -Force -Path $profilePath | Out-Null
    $arguments = @(
        "--remote-debugging-port=$Port",
        '--remote-debugging-address=127.0.0.1',
        "--user-data-dir=$profilePath",
        '--no-first-run',
        '--no-default-browser-check',
        $Url
    )
    Start-Process -FilePath $chromePath -ArgumentList $arguments | Out-Null

    $deadline = (Get-Date).AddSeconds($TimeoutSeconds)
    do {
        Start-Sleep -Milliseconds 500
        $healthy = Get-ChromeEndpoint
    } while (-not $healthy -and (Get-Date) -lt $deadline)

    if (-not $healthy) {
        throw "Chrome did not expose a valid DevTools endpoint at $endpoint within $TimeoutSeconds seconds."
    }
}

Write-Output "CDP endpoint: $endpoint"
Write-Output "Chrome executable: $chromePath"
Write-Output "QA profile: $profilePath"
Write-Output "Browser: $($healthy.Browser)"
