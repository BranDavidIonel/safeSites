<?php
// index.php - SafeSites Simple App

// Handle POST request
$hostsFileContent = '';
$fileName = 'custom_hosts.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileName = trim($_POST['fileName'] ?? 'custom_hosts.txt');
    $fileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
    if (empty($fileName)) {
        $fileName = 'custom_hosts.txt';
    }

    $includeDefaults = isset($_POST['includeDefaults']);
    $customHostsText = $_POST['customHosts'] ?? '';
    $lines = preg_split("/\r\n|\n|\r/", $customHostsText);

    $content = "# SafeSites generated hosts file\n";
    $content .= "# Generated: " . gmdate('Y-m-d H:i:s') . " (UTC)\n\n";

    if ($includeDefaults) {
        $content .= "127.0.0.1 localhost\n";
        $content .= "127.0.0.1 localhost.localdomain\n";
        $content .= "127.0.0.1 local\n";
        $content .= "255.255.255.255 broadcasthost\n";
        $content .= "::1 localhost\n\n";
    }

    if (!empty(trim($customHostsText))) {
        $content .= "# Custom host records (temporary, user provided)\n";
        foreach ($lines as $line) {
            $domain = trim($line);
            if ($domain === '') continue;
            $domain = preg_replace('/\s+/', '', $domain);
            $content .= "0.0.0.0 {$domain}\n";
        }
        $content .= "\n";
    }

    $hostsFileContent = $content;

    // Handle download if requested
    if (isset($_POST['download']) && !empty($hostsFileContent)) {
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        echo $hostsFileContent;
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeSites - Simple App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 1rem; box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,.05); }
        textarea { resize: vertical; font-family: monospace; }
        pre.preview {
            background: #0f172a; color: #d1d5db;
            padding: 1rem; border-radius: .5rem; overflow-x: auto; max-height: 300px;
        }
    </style>
</head>
<body class="container py-5">

<div class="text-center mb-5">
    <h1 class="fw-bold text-primary">SafeSites</h1>
    <p class="lead">
        <strong>What is the hosts.txt file?</strong><br>
        The <code>hosts</code> file maps hostnames to IPs.
        <br><br>
        <strong>Windows:</strong> <code>C:\Windows\System32\drivers\etc\hosts</code><br>
        <strong>Linux / macOS:</strong> <code>/etc/hosts</code>
        <br><br>
        <span class="text-success fw-semibold">SafeSites helps you block distractions or unsafe websites easily.</span>
    </p>
</div>

<div class="row">
    <!-- Left Column: Form -->
    <div class="col-lg-6 mb-4">
        <div class="card p-4">
            <h3 class="mb-3"><i class="bi bi-sliders"></i> Generate your custom hosts file</h3>
            <form method="POST">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="includeDefaults" name="includeDefaults"
                        <?php echo isset($_POST['includeDefaults']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="includeDefaults">
                        Include predefined system entries (127.0.0.1 localhost, etc.)
                    </label>
                </div>

                <div class="mb-3">
                    <label for="fileName" class="form-label fw-semibold">Filename:</label>
                    <input type="text" class="form-control" id="fileName" name="fileName"
                           value="<?php echo htmlspecialchars($fileName); ?>">
                    <div class="form-text">Default: <code>custom_hosts.txt</code></div>
                </div>

                <div class="mb-3">
                    <label for="customHosts" class="form-label fw-semibold">Custom hosts (one per line):</label>
                    <textarea class="form-control" id="customHosts" name="customHosts" rows="6"
                              placeholder="example.com&#10;ads.example.net"><?php
                        echo isset($_POST['customHosts']) ? htmlspecialchars($_POST['customHosts']) : ''; ?></textarea>
                    <div class="form-text">Each line will be added as <code>0.0.0.0 domain.com</code></div>
                </div>

                <button type="submit" class="btn btn-success mb-3">
                    <i class="bi bi-sliders"></i> Generate Preview
                </button>
                <?php if (!empty($hostsFileContent)): ?>
                    <button type="submit" name="download" value="1" class="btn btn-primary mb-3 w-100">
                        <i class="bi bi-download"></i> Download Hosts File
                    </button>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Right Column: Preview -->
    <?php if (!empty($hostsFileContent)): ?>
        <div class="col-lg-6 mb-4">
            <div class="card p-4 h-100">
                <h5 class="fw-semibold mb-3">Preview of your hosts file:</h5>
                <textarea class="form-control mb-3" rows="15" readonly><?php echo htmlspecialchars($hostsFileContent); ?></textarea>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if (!empty($fileName) && !empty($hostsFileContent)) : ?>
    <div class="row">
        <div class="mt-3 alert alert-info">
            <strong>After downloading your hosts file:</strong> follow these instructions to apply it on your machine:
        </div>

        <div class="mt-3">
            <h6>Linux / macOS (terminal)</h6>
            <pre class="preview">
# Backup existing hosts
sudo cp /etc/hosts /etc/hosts.bak
# Copy downloaded file
sudo cp ~/Downloads/<?php echo htmlspecialchars($fileName); ?> /etc/hosts
        </pre>

            <h6 class="mt-3">Windows (PowerShell as Administrator)</h6>
            <pre class="preview">
# Backup original
copy $env:SystemRoot\System32\drivers\etc\hosts $env:SystemRoot\System32\drivers\etc\hosts.bak
# Copy downloaded file
Copy-Item -Path "$env:USERPROFILE\Downloads/<?php echo htmlspecialchars($fileName); ?>" -Destination "$env:SystemRoot\System32\drivers\etc\hosts" -Force
        </pre>

            <hr>
            <h6>Extra tips for stronger protection</h6>
            <ul class="small">
                <li><strong>Linux:</strong> use <code>sudo chattr +i /etc/hosts</code> after copying to prevent modification even by root.</li>
                <li><strong>Windows:</strong> restrict write permissions with <code>icacls</code> to Admins/SYSTEM only.</li>
                <li><strong>Sharing password to a friend (theoretical):</strong> securely share passphrase for encrypted backups. Never email passwords in plain text.</li>
            </ul>
        </div>
    </div>
<?php endif; ?>


<footer class="text-center mt-5 text-muted small">
    &copy; <?php echo date('Y'); ?> SafeSites Project — simple PHP app.
</footer>

</body>
</html>
