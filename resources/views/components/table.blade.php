
<table {{ $attributes->merge(['class' => 'w-full text-sm text-left rtl:text-right text-body']) }}>
    <thead {{ $thead->attributes->class(['text-sm text-body border-b border-t border-zinc-500']) }}>
        <tr>
            {{ $thead ?? null }}
        </tr>
    </thead>
    <tbody {{ $tbody->attributes->class(['']); }}>
        {{ $tbody ?? null }}
    </tbody>
    <tfoot>
        {{ $tfoot ?? null }}
    </tfoot>
</table>
