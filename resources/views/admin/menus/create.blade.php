@extends('layouts.admin')

@section('title', 'Add New Menu')
@section('page-title', 'Create Dish')
@section('page-subtitle', 'Enter the details of your new dish to add it to the menu.')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white p-8 lg:p-12 rounded-[2.5rem] border border-slate-200 shadow-sm">
        <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Dish Name</label>
                    <input type="text" name="nama" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all placeholder:text-slate-300 @error('nama') ring-2 ring-rose-500 @enderror" placeholder="e.g. Special Fried Rice" value="{{ old('nama') }}" required>
                    @error('nama') <p class="text-rose-600 text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Category</label>
                    <select name="category_id" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all appearance-none" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Price (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                        <input type="number" name="harga" class="w-full bg-slate-50 border-none rounded-2xl pl-12 pr-5 py-4 font-bold text-slate-900 focus:ring-2 focus:ring-indigo-600 transition-all @error('harga') ring-2 ring-rose-500 @enderror" placeholder="25000" value="{{ old('harga') }}" required>
                    </div>
                    @error('harga') <p class="text-rose-600 text-[10px] font-bold mt-2 ml-1 uppercase tracking-widest">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Description (Optional)</label>
                    <textarea name="deskripsi" class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 font-medium text-slate-600 focus:ring-2 focus:ring-indigo-600 transition-all" rows="3" placeholder="Ingredients, taste, etc...">{{ old('deskripsi') }}</textarea>
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label class="block text-slate-400 text-[10px] font-bold uppercase tracking-widest mb-3 ml-1">Menu Photo</label>
                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] h-64 relative group overflow-hidden flex flex-col items-center justify-center hover:border-indigo-400 transition-all">
                        <!-- Preview Image -->
                        <img id="image-preview" class="absolute inset-0 w-full h-full object-cover hidden z-0">
                        
                        <input type="file" name="gambar" id="image-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/*">
                        
                        <div id="upload-placeholder" class="text-center group-hover:scale-110 transition-transform relative z-20 pointer-events-none px-6">
                            <i class="bi bi-cloud-arrow-up text-4xl text-slate-300 group-hover:text-indigo-600 transition-colors"></i>
                            <p class="text-slate-400 text-[10px] font-extrabold mt-4 uppercase tracking-widest leading-relaxed">Drag & Drop or Click to Upload</p>
                        </div>
                    </div>
                </div>

                <!-- Availability Toggle -->
                <div class="md:col-span-2 bg-slate-50 p-6 rounded-2xl flex items-center justify-between border border-slate-100">
                    <div>
                        <div class="text-slate-900 font-extrabold text-sm">Available for Ordering</div>
                        <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Mark this dish as ready to be served</div>
                    </div>
                    <div class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_available" id="is_available" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-50 flex flex-col sm:flex-row gap-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white font-extrabold py-5 rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200">
                    Save Menu Item
                </button>
                <a href="{{ route('admin.menus.index') }}" class="px-10 py-5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('opacity-0');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
