[CmdletBinding()]
param(
    [Parameter(Mandatory = $true)]
    [string]$NodePath,

    [Parameter(Mandatory = $true)]
    [string]$ScriptPath,

    [int]$TimeoutSeconds = 30,

    [Parameter(Mandatory = $true)]
    [string]$NodeArgumentsBase64
)

$ErrorActionPreference = 'Stop'

function ConvertTo-WindowsCommandLineArgument {
    param([Parameter(Mandatory = $true)][string]$Value)

    if ($Value -notmatch '[\s"]') {
        return $Value
    }

    return '"' + ($Value -replace '(\\*)"', '$1$1\\"') + '"'
}

if (-not (Test-Path -LiteralPath $NodePath -PathType Leaf)) {
    throw "Windows Node executable was not found: $NodePath"
}
if (-not (Test-Path -LiteralPath $ScriptPath -PathType Leaf)) {
    throw "Windows Node script was not found: $ScriptPath"
}
if ($TimeoutSeconds -lt 1) {
    throw 'TimeoutSeconds must be at least 1.'
}

$decodedArguments = [System.Text.Encoding]::UTF8.GetString([System.Convert]::FromBase64String($NodeArgumentsBase64))
$decodedNodeArguments = $decodedArguments | ConvertFrom-Json
$NodeArguments = if ($null -eq $decodedNodeArguments) { @() } else { @($decodedNodeArguments) }
$arguments = @($ScriptPath) + $NodeArguments | ForEach-Object {
    ConvertTo-WindowsCommandLineArgument ([string]$_)
}
$startInfo = [System.Diagnostics.ProcessStartInfo]::new()
$startInfo.FileName = $NodePath
$startInfo.Arguments = [string]::Join(' ', $arguments)
$startInfo.UseShellExecute = $false
$startInfo.CreateNoWindow = $true
$startInfo.RedirectStandardOutput = $true
$startInfo.RedirectStandardError = $true

$process = [System.Diagnostics.Process]::new()
$process.StartInfo = $startInfo
if (-not $process.Start()) {
    throw 'Windows Node helper did not start.'
}
$stdoutTask = $process.StandardOutput.ReadToEndAsync()
$stderrTask = $process.StandardError.ReadToEndAsync()
if (-not $process.WaitForExit($TimeoutSeconds * 1000)) {
    $process.Kill()
    $process.WaitForExit()
    throw "Windows Node helper exceeded its $TimeoutSeconds second timeout and was stopped."
}
$process.WaitForExit()
[Console]::Out.Write($stdoutTask.GetAwaiter().GetResult())
[Console]::Error.Write($stderrTask.GetAwaiter().GetResult())
[Console]::Out.Write("`n__TNET_WINDOWS_NODE_EXIT_CODE=$($process.ExitCode)`n")
