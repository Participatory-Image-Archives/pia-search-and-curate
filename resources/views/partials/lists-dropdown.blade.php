<select name="lists" id="lists" class="text-xs py-2 px-3 bg-black text-white rounded-full"
    onchange="window.location = this.value">
    <option value="">Lists…</option>
    <option value="{{ route('collections.index') }}">Collections</option>
    <option value="{{ route('keywords.index') }}">Keywords</option>
    <option value="{{ route('people.index') }}">People</option>
</select>