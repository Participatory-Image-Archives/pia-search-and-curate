<select name="lists" id="lists" class="text-xs px-2 bg-black text-white rounded-full" style="height: 26px;"
    onchange="window.location = this.value">
    <option value="">Lists…</option>
    <option value="{{ route('collections.index') }}">Collections</option>
    <option value="{{ route('keywords.index') }}">Keywords</option>
    <option value="{{ route('people.index') }}">People</option>
</select>