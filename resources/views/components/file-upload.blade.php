@props([
    'wireModel' => 'attach_file', 
    'label' => 'Upload File', 
    'accept' =>  null, 
    'maxSizeText' => null, 
    'showTips' => true, 
    'error' => null, 
    'uploadProgressModel' => 'uploadProgress', 
    'file' => null
    ])

<div>
    <h3 class="kantumruy-bold text-lg text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
        {{ $label }}
    </h3>

    <div
        x-data="{
            isDragging: false,
            dragCounter: 0,
            fileSizeError: '',
            uploading: false,
            progress: 0,
            maxFileSize: 50 * 1024 * 1024, 
            handleDragEnter() {
                this.dragCounter++;
                this.isDragging = true;
            },
            handleDragLeave() {
                this.dragCounter--;
                if (this.dragCounter === 0) {
                    this.isDragging = false;
                }
            },
            handleDrop(e) {
                this.isDragging = false;
                this.dragCounter = 0;
            },
            validateFileSize(event) {
                this.fileSizeError = '';
                const file = event.target.files[0];
                if (file && file.size > this.maxFileSize) {
                    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                    this.fileSizeError = `ឯកសារមានទំហំ ${sizeMB} MB ធំពេក។`;
                    event.target.value = ''; // Clear the file input
                    return false;
                }
                return true;
            }
        }"
        x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false; progress = 0"
        x-on:livewire-upload-error="uploading = false; progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress"
        @dragenter.prevent="handleDragEnter"
        @dragleave.prevent="handleDragLeave"
        @dragover.prevent
        @drop.prevent="handleDrop"
    >
        @if (!$file)
            <!-- Drag & Drop Zone -->
            <div
                @click="$refs.fileInput.click()"
                :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'"
                class="relative border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50"
            >
                <input
                    type="file"
                    wire:model="{{ $wireModel }}"
                    x-ref="fileInput"
                    class="hidden"
                    accept="{{ $accept }}"
                    @change="validateFileSize($event)"
                >

                <div class="space-y-3">
                    <div class="flex justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                          </svg>                          
                    </div>
                    <div>
                        <p class="text-lg kantumruy-bold text-gray-700">Drop file or click to browse</p>
                        <p class="text-sm battambang text-gray-500 mt-2">{{ strtoupper(str_replace(['.', ','], ['', ', '], $accept)) }} - {{ $maxSizeText }}</p>
                       
                    </div>
                    <div x-show="uploading" class="text-blue-600 battambang">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <div class="text-sm">កំពុងផ្ទុកឯកសារ...</div>

                            <!-- Upload Progress Bar -->
                            <div class="w-full max-w-md mt-2" x-show="progress > 0">
                                <div class="flex justify-between text-xs mb-1">
                                    <span>ជំហានទី១: ផ្ទុកឡើង</span>
                                    <span x-text="progress + '%'"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- File Preview with Compression Info -->
            <div class="border border-green-300 bg-green-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3 flex-1">
                        <div class="flex-shrink-0">
                            @php
                                $extension = $file->getClientOriginalExtension();
                                $iconColor = match($extension) {
                                    'pdf' => 'text-red-600',
                                    'doc', 'docx' => 'text-blue-600',
                                    'png', 'jpg', 'jpeg' => 'text-green-600',
                                    default => 'text-gray-600'
                                };
                            @endphp
                            <span class="material-icons-round text-5xl {{ $iconColor }}">
                                {{ in_array($extension, ['png', 'jpg', 'jpeg']) ? 'image' : 'insert_drive_file' }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="kantumruy-bold text-gray-900">{{ $file->getClientOriginalName() }}</p>
                            <p class="text-sm battambang text-gray-600">{{ number_format($file->getSize() / 1024 / 1024, 2) }} MB</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        wire:click="removeFile"
                        class="flex items-center gap-2 px-3 py-2 text-red-600 hover:bg-red-100 rounded-lg transition battambang flex-shrink-0"
                    >
                        <span class="material-icons-round">delete</span>
                        លុប
                    </button>
                </div>

                
            </div>
        @endif

        <!-- Server-side validation error -->
        @if($error)
            <div class="text-red-600 text-sm mt-2 battambang flex items-center gap-1">
                <span class="material-icons-round text-base">error</span>
                {{ $error }}
            </div>
        @endif

        <!-- Client-side file size error -->
        <div x-show="fileSizeError" x-text="fileSizeError"
             class="text-red-600 text-sm mt-2 battambang flex items-center gap-1 bg-red-50 border border-red-200 rounded-lg p-3">
        </div>

        

        
    </div>
</div>
