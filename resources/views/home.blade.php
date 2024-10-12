@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>I miei upload</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($uploads->count())
            <div class="list-group">
                @foreach ($uploads as $upload)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <small>{{ $upload->created_at->diffForHumans() }}</small>
                            <button class="btn btn-sm btn-outline-secondary ms-2 copy-btn"
                                data-text-id="text-content-{{ $upload->id }}">
                                Copia
                            </button>
                        </div>

                        @if ($upload->text_content)
                            <p class="mb-1">
                                <pre id="text-content-{{ $upload->id }}" style="white-space: pre-wrap;">
                                {{ $upload->text_content }}
                            </pre>
                            </p>
                        @endif

                        @if ($upload->file_path)
                            <p class="mb-1"><strong>File:</strong> {{ $upload->file_name }}</p>
                            <a href="{{ route('uploads.download', $upload->id) }}"
                                class="btn btn-sm btn-primary">Scarica</a>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Paginazione -->
            <div class="mt-3">
                {{ $uploads->links() }}
            </div>
        @else
            <div class="text-center my-36">
                <i class="fas fa-cloud-upload-alt fa-5x"></i>
                <h2 class="my-10">Nessun upload</h2>
            </div>
        @endif
    </div>


    <!-- Script per gestire il pulsante "Copia" -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('.copy-btn');

            copyButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const textId = this.getAttribute('data-text-id');
                    const textElement = document.getElementById(textId);
                    const text = textElement.innerText;

                    // Crea un elemento di input temporaneo
                    const tempInput = document.createElement('textarea');
                    tempInput.value = text;
                    document.body.appendChild(tempInput);

                    // Seleziona e copia il testo
                    tempInput.select();
                    tempInput.setSelectionRange(0, 99999); // Per dispositivi mobili

                    try {
                        const successful = document.execCommand('copy');
                        if (successful) {
                            // Feedback all'utente
                            this.textContent = 'Copiato!';
                            this.classList.remove('btn-outline-secondary');
                            this.classList.add('btn-success');
                            setTimeout(() => {
                                this.textContent = 'Copia';
                                this.classList.remove('btn-success');
                                this.classList.add('btn-outline-secondary');
                            }, 2000);
                        } else {
                            alert('Copia fallita');
                        }
                    } catch (err) {
                        alert('Copia fallita');
                    }

                    // Rimuove l'elemento di input temporaneo
                    document.body.removeChild(tempInput);
                });
            });
        });
    </script>
@endsection
