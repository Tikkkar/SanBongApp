$ErrorActionPreference = "Stop"

Write-Host "Fetching latest PHP 8.2 link..."
$html = Invoke-WebRequest -Uri "https://windows.php.net/downloads/releases/" -UseBasicParsing | Select-Object -ExpandProperty Content
if ($html -match "php-8\.2\.\d+-nts-Win32-vs16-x64\.zip") {
    $zipName = $matches[0]
    $phpUrl = "https://windows.php.net/downloads/releases/$zipName"
    Write-Host "Found URL: $phpUrl"
} else {
    Write-Host "Could not find a PHP 8.2 zip file!"
    exit 1
}

Write-Host "Creating C:\php directory..."
New-Item -ItemType Directory -Force -Path "C:\php" | Out-Null

Write-Host "Downloading $zipName..."
$phpZip = "C:\php\php.zip"
Invoke-WebRequest -Uri $phpUrl -OutFile $phpZip

Write-Host "Extracting PHP..."
Expand-Archive -Path $phpZip -DestinationPath "C:\php" -Force
Remove-Item $phpZip

Write-Host "Configuring php.ini..."
Copy-Item "C:\php\php.ini-development" "C:\php\php.ini"
$ini = Get-Content "C:\php\php.ini"
$ini = $ini -replace "^;extension=curl", "extension=curl"
$ini = $ini -replace "^;extension=fileinfo", "extension=fileinfo"
$ini = $ini -replace "^;extension=mbstring", "extension=mbstring"
$ini = $ini -replace "^;extension=openssl", "extension=openssl"
$ini = $ini -replace "^;extension=pdo_mysql", "extension=pdo_mysql"
$ini = $ini -replace "^;extension=pdo_sqlite", "extension=pdo_sqlite"
$ini = $ini -replace "^;extension=zip", "extension=zip"
$ini = $ini -replace "^;extension_dir = `"ext`"", "extension_dir = `"ext`""
Set-Content -Path "C:\php\php.ini" -Value $ini

Write-Host "Adding C:\php to User PATH..."
$userPath = [Environment]::GetEnvironmentVariable("PATH", [EnvironmentVariableTarget]::User)
if ($userPath -notmatch "C:\\php") {
    $newPath = $userPath + ";C:\php"
    [Environment]::SetEnvironmentVariable("PATH", $newPath, [EnvironmentVariableTarget]::User)
    $env:PATH = $newPath
}

Write-Host "Downloading Composer..."
$composerTarget = "C:\php\composer.phar"
Invoke-WebRequest -Uri "https://getcomposer.org/composer-stable.phar" -OutFile $composerTarget

Write-Host "Creating Composer start script..."
Set-Content -Path "C:\php\composer.bat" -Value "@php `"%~dp0composer.phar`" %*"

Write-Host "Installation Complete!"
C:\php\php.exe -v
C:\php\composer.bat -V
