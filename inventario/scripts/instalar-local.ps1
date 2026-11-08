param(
    [Parameter(Mandatory=$true)]
    [string]$GlpiRoot
)
$ErrorActionPreference = 'Stop'
$source = Join-Path (Split-Path -Parent $PSScriptRoot) 'app\glpi'
if (-not (Test-Path -LiteralPath (Join-Path $source 'version\12.0.1') -PathType Leaf)) {
    throw 'No se encuentra la copia GLPI 12.0.1 dentro de app\glpi.'
}
$target = [IO.Path]::GetFullPath($GlpiRoot)
if (Test-Path -LiteralPath $target) {
    $items = @(Get-ChildItem -LiteralPath $target -Force -ErrorAction SilentlyContinue)
    if ($items.Count -gt 0) { throw "Destino no vacío: $target. No se sobrescribió nada. Usa una carpeta vacía para evitar mezclar código o datos." }
} else {
    New-Item -ItemType Directory -Force -Path $target | Out-Null
}
& robocopy $source $target /E /R:1 /W:1 /NFL /NDL /NJH /NJS /NP
if ($LASTEXITCODE -ge 8) { throw "Falló la copia (código robocopy $LASTEXITCODE). Revisa el destino." }
foreach ($folder in @('config','files','marketplace')) { New-Item -ItemType Directory -Force -Path (Join-Path $target $folder) | Out-Null }
Write-Host "GLPI Fundación Luz copiado a $target"
Write-Host 'El script no importa ni modifica bases de datos.'
Write-Host 'Asegúrate de que Apache pueda escribir en config y files durante la instalación.'
