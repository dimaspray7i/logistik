@props([
    'action',        // form action route
    'documentTypes', // collection of DocumentType enum cases
])

<div class="crm-card space-y-5"
     x-data="{
         fileName: '',
         fileSize: '',
         hasFile: false,
         isDragging: false,
         handleFile(files) {
             if (!files || files.length === 0) return;
             const f = files[0];
             this.fileName = f.name;
             this.fileSize = f.size < 1024*1024
                 ? Math.round(f.size/1024) + ' KB'
                 : (f.size/1024/1024).toFixed(1) + ' MB';
             this.hasFile = true;
             // Sync to real input
             const dt = new DataTransfer();
             dt.items.add(f);
             document.getElementById('doc_file_real').files = dt.files;
         },
         clearFile() {
             this.fileName = '';
             this.fileSize = '';
             this.hasFile = false;
             document.getElementById('doc_file_real').value = '';
         }
     }">

    {{-- Header --}}
    <div class="border-b border-gray-100 pb-4">
        <h2 class="font-poppins font-bold text-base text-gray-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            Unggah Dokumen Baru
        </h2>
        <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG &middot; Maks. 10 MB</p>
    </div>

    <form method="POST"
          action="{{ $action }}"
          enctype="multipart/form-data"
          class="space-y-4"
          id="doc-upload-form">
        @csrf

        {{-- Judul Dokumen --}}
        <div>
            <label for="doc_title" class="crm-label">
                Judul Dokumen <span class="text-primary">*</span>
            </label>
            <input id="doc_title"
                   type="text"
                   name="title"
                   value="{{ old('title') }}"
                   placeholder="Contoh: Surat Jalan TTD / Invoice No. 001"
                   required
                   class="crm-input">
            @error('title')
                <p class="text-primary text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipe Dokumen --}}
        <div>
            <label for="doc_type" class="crm-label">
                Tipe Dokumen <span class="text-primary">*</span>
            </label>
            <select id="doc_type" name="type" required class="crm-input">
                @foreach ($documentTypes as $type)
                    <option value="{{ $type->value }}" @selected(old('type') == $type->value)>
                        {{ $type->label() }}
                    </option>
                @endforeach
            </select>
            @error('type')
                <p class="text-primary text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Custom File Picker --}}
        <div>
            <label class="crm-label">
                Pilih File <span class="text-primary">*</span>
            </label>

            {{-- Real file input (hidden, used for actual upload) --}}
            <input id="doc_file_real"
                   type="file"
                   name="file"
                   accept=".pdf,.jpg,.jpeg,.png"
                   required
                   class="sr-only"
                   @change="handleFile($event.target.files)">

            {{-- Drop Zone / Custom Picker --}}
            <div
                class="relative border-2 border-dashed rounded-xl transition-all duration-200 cursor-pointer
                       {{ old('file') ? 'border-primary/40 bg-primary/5' : 'border-gray-200 bg-gray-50/60' }}
                       hover:border-primary/50 hover:bg-primary/5"
                :class="{
                    'border-primary bg-primary/10': isDragging,
                    'border-primary/40 bg-primary/5': hasFile,
                    'border-gray-200 bg-gray-50/60': !isDragging && !hasFile
                }"
                @click="$refs.fileZone.click()"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="isDragging = false; handleFile($event.dataTransfer.files)"
                x-ref="dropZone">

                {{-- Trigger button ref --}}
                <button type="button" class="sr-only" x-ref="fileZone" @click.stop="document.getElementById('doc_file_real').click()"></button>

                {{-- No file selected state --}}
                <div x-show="!hasFile" class="flex flex-col items-center justify-center gap-2 py-8 px-4 text-center pointer-events-none">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">
                            <span class="text-primary underline underline-offset-2">Pilih file</span>
                            <span class="hidden sm:inline"> atau drag &amp; drop di sini</span>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">PDF, JPG, PNG &middot; Maks. 10 MB</p>
                    </div>
                </div>

                {{-- File selected state --}}
                <div x-show="hasFile" x-cloak class="flex items-center gap-3 py-4 px-4 pointer-events-none">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 border border-primary/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-900 truncate" x-text="fileName"></p>
                        <p class="text-xs text-gray-500" x-text="fileSize"></p>
                    </div>
                    <div class="shrink-0 w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Clear file button --}}
            <button type="button"
                    x-show="hasFile"
                    x-cloak
                    @click="clearFile()"
                    class="mt-1.5 text-xs text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Hapus pilihan
            </button>

            @error('file')
                <p class="text-primary text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button — always full width on mobile --}}
        <div class="pt-1">
            <button type="submit"
                    class="btn-primary w-full justify-center py-3 text-sm font-semibold"
                    :class="{ 'opacity-50 cursor-not-allowed': !hasFile }"
                    :disabled="!hasFile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Upload Dokumen
            </button>
        </div>
    </form>

</div>
