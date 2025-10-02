<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeSites - Create a custom hosts file</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,.05);
        }
        textarea {
            resize: vertical;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, "Roboto Mono", monospace;
        }
        pre.preview {
            background: #0f172a;
            color: #d1d5db;
            padding: 1rem;
            border-radius: .5rem;
            overflow-x: auto;
            max-height: 300px;
        }
    </style>
</head>
<body class="container py-5">

<div class="text-center mb-5">
    <h1 class="fw-bold text-primary">SafeSites</h1>
    <p class="lead">
        <strong>What is the hosts.txt file?</strong><br>
        The <code>hosts.txt</code> (or just <code>hosts</code>) file is a plain text file used by your operating system
        to map hostnames (like <em>example.com</em>) to IP addresses.
        <br><br>
        <strong>Windows:</strong> located at <code>C:\Windows\System32\drivers\etc\hosts</code>
        <strong>Linux / macOS:</strong> located at <code>/etc/hosts</code>
        <br><br>
        Editing this file lets you block or redirect domains.
        <br>
        <span class="text-success fw-semibold">The purpose of SafeSites is to help people like me avoid unwanted traffic, distractions,
            or unsafe websites — intentionally or by mistake.</span>
    </p>
</div>

<div class="row">
    <!-- Left Column: Form -->
    <div class="col-lg-6 mb-4">
        <div class="card p-4">
            <h3 class="mb-3"><i class="bi bi-sliders"></i> Generate your custom hosts file</h3>
            <form method="POST" action="{{ route('generate.hosts.guest') }}">
                @csrf

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="includeDefaults" name="includeDefaults" {{ old('includeDefaults', 'checked') }}>
                    <label class="form-check-label" for="includeDefaults">
                        Include predefined system entries (127.0.0.1 localhost, etc.)
                    </label>
                </div>

                <div class="mb-3">
                    <label for="fileName" class="form-label fw-semibold">Filename:</label>
                    <input type="text" class="form-control" id="fileName" name="fileName" value="{{ old('fileName', 'custom_hosts.txt') }}">
                    <div class="form-text">You can choose a different name for your hosts file (default: <code>custom_hosts.txt</code>).</div>
                </div>

                <div class="mb-3">
                    <label for="customHosts" class="form-label fw-semibold">Custom hosts (one per line):</label>
                    <textarea class="form-control" id="customHosts" name="customHosts" rows="6"
                              placeholder="example.com&#10;ads.example.net">{{ old('customHosts') }}</textarea>
                    <div class="form-text">Each line will be added as <code>0.0.0.0 domain.com</code></div>
                </div>

                <button type="submit" class="btn btn-success mb-3">
                    <i class="bi bi-sliders"></i> Generate Preview
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Preview + Download + Instructions -->
    @if(isset($hostsFileContent))
        <div class="col-lg-6 mb-4">
            <div class="card p-4 h-100">
                <h5 class="fw-semibold mb-3">Preview of your hosts file:</h5>
                <textarea class="form-control mb-3" rows="15" readonly>{{ $hostsFileContent }}</textarea>
                @if(!empty($hostsFileContent))
                    <form method="POST" action="{{ route('download.hosts.guest') }}">
                        @csrf
                        <input type="hidden" name="hostsFileContent" value="{{ htmlentities($hostsFileContent) }}">
                        <input type="hidden" name="fileName" value="{{ old('fileName', 'custom_hosts.txt') }}">
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-download"></i> Download Hosts File
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
<div class="row">
    @if(isset($fileName) && !empty($fileName))
        <div class="mt-3 alert alert-info">
            <strong>After downloading your hosts file:</strong> follow these instructions to apply it on your machine:
        </div>

        <div class="mt-3">
            <h6>Linux / macOS (terminal)</h6>
            <pre class="preview">
# Backup existing hosts
sudo cp /etc/hosts /etc/hosts.bak
# Copy downloaded file
sudo cp ~/Downloads/{{ $fileName }} /etc/hosts
                </pre>

            <h6 class="mt-3">Windows (PowerShell as Administrator)</h6>
            <pre class="preview">
# Backup original
copy $env:SystemRoot\System32\drivers\etc\hosts $env:SystemRoot\System32\drivers\etc\hosts.bak
# Copy downloaded file
Copy-Item -Path "$env:USERPROFILE\Downloads\{{ $fileName }}" -Destination "$env:SystemRoot\System32\drivers\etc\hosts" -Force

                </pre>



            {{--            <div class="alert alert-warning mt-3 small">--}}
            {{--                <strong>Important:</strong> If you encrypt the file, the system cannot use the encrypted copy directly. Decrypt before applying.--}}
            {{--            </div>--}}

            <hr>
            <h6>Extra tips for stronger protection</h6>
            <ul class="small">
                <li><strong>Linux:</strong> use <code>sudo chattr +i /etc/hosts</code> after copying to prevent modification even by root.</li>
                <li><strong>Windows:</strong> restrict write permissions with <code>icacls</code> to Admins/SYSTEM only.</li>
                <li><strong>Sharing password to a friend (theoretical):</strong> securely share passphrase for encrypted backups. Never email passwords in plain text.</li>
            </ul>
        </div>
    @endif

</div>

<footer class="text-center mt-5 text-muted small">
    &copy; {{ date('Y') }} SafeSites Project — created to protect and simplify browsing.
</footer>

</body>
</html>
