<x-application-tools-wrapper>
    <x-binary-tools :tool="$tool" :title="__('tools.enterPasteBinaryConvert')" :placeholder="__('tools.pasteBinaryHere')" :label="__('tools.convertToDecimal')" :nl2br="true"
        :results="$results ?? null" />
</x-application-tools-wrapper>
