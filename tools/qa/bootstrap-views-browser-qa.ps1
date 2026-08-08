[CmdletBinding()]
param(
    [int]$ChromePort = 9222,
    [int]$BridgePort = 9223,
    [string]$ViewsUrl = 'https://teachers-net.ddev.site/wp-admin/admin.php?page=cfm-views&version_id=17',
    [switch]$ConfigureBridge
)

$ErrorActionPreference = 'Stop'
$launcher = Join-Path $PSScriptRoot 'launch-chrome-cdp-9222.ps1'
$chromeEndpoint = "http://127.0.0.1:$ChromePort/json/version"
$bridgeEndpoint = "http://127.0.0.1:$BridgePort/json/version"

function Get-Endpoint($uri) {
    try { return Invoke-RestMethod -Uri $uri -TimeoutSec 3 } catch { return $null }
}

& $launcher -Url $ViewsUrl -Port $ChromePort | Write-Output
$chrome = Get-Endpoint $chromeEndpoint
if (-not $chrome -or $chrome.Browser -notmatch '^Chrome/') {
    throw "ENGINEERING INPUT REQUIRED: Windows CDP check failed at $chromeEndpoint."
}

$listen = "0.0.0.0:$BridgePort"
$netsh = Join-Path $env:windir 'System32\netsh.exe'
if ($ConfigureBridge) {
    try {
        $result = Start-Process -FilePath $netsh -ArgumentList @('interface','portproxy','add','v4tov4',"listenaddress=0.0.0.0","listenport=$BridgePort",'connectaddress=127.0.0.1',"connectport=$ChromePort") -Wait -PassThru -WindowStyle Hidden
        if ($result.ExitCode -ne 0) { throw "netsh exited with code $($result.ExitCode)" }
    } catch {
        throw "ENGINEERING INPUT REQUIRED: port-proxy configuration requires elevated Windows networking permission. Run this script elevated with -ConfigureBridge."
    }
}

$bridge = Get-Endpoint $bridgeEndpoint
if (-not $bridge) {
    throw "ENGINEERING INPUT REQUIRED: WSL CDP bridge unavailable at $bridgeEndpoint. Windows CDP is healthy; run this bootstrap elevated with -ConfigureBridge, then retry."
}
if ($bridge.Browser -notmatch '^Chrome/') {
    throw "ENGINEERING INPUT REQUIRED: bridge answered but does not identify the expected Chrome QA browser."
}

$pages = try { Invoke-RestMethod -Uri "http://127.0.0.1:$BridgePort/json/list" -TimeoutSec 3 } catch { $null }
$page = @($pages) | Where-Object { $_.url -eq $ViewsUrl -or $_.url -like "$ViewsUrl&*" } | Select-Object -First 1
if (-not $page) {
    throw "ENGINEERING INPUT REQUIRED: authenticated Views page was not discoverable at $ViewsUrl through $bridgeEndpoint."
}

[ordered]@{
    status = 'READY'
    chrome_endpoint = $chromeEndpoint
    bridge_endpoint = $bridgeEndpoint
    browser = $bridge.Browser
    views_url = $ViewsUrl
    page_id = $page.id
    screenshot_rule = 'Automation must write directly to a WSL path and verify nonzero size before hopper collection.'
} | ConvertTo-Json -Compress | Write-Output
