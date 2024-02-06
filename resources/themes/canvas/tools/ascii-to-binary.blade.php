<x-application-tools-wrapper>
    <x-binary-tools :tool="$tool" :title="__('tools.enterPasteAsciiConvert')" :placeholder="__('tools.pasteAsciiHere')" :label="__('tools.convertToBinary')" :nl2br="true"
        :results="$results ?? null" />
</x-application-tools-wrapper>
