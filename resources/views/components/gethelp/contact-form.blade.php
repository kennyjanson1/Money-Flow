<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Contact Support</h2>
    
    <form action="{{ route('gethelp.contact') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Subject</label>
            <input 
                type="text" 
                name="subject"
                value="{{ old('subject') }}"
                required
                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Brief description of your issue"
            >
            @error('subject')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message</label>
            <textarea 
                name="message"
                required
                rows="4"
                class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="Describe your issue in detail..."
            >{{ old('message') }}</textarea>
            @error('message')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>
        
        <button 
            type="submit"
            class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition"
        >
            Send Message
        </button>
    </form>
</div>