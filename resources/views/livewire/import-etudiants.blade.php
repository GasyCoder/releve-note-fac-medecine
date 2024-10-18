<!-- resources/views/livewire/import-etudiants.blade.php -->
<div>
    <h2 class="text-2xl font-bold mb-4">Importer les étudiants</h2>
    <form wire:submit="import" class="mb-4">
        <div class="mb-3">
            <label for="file" class="block mb-2">Fichier Excel</label>
            <input type="file" class="form-control" id="file" wire:model="file">
            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-primary">Importer</button>
    </form>
</div>
