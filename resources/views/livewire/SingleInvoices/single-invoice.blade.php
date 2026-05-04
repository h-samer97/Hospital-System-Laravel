<div>
    @if($show_table)
        @include('livewire.single_invoices.table')
    @else
        @include('livewire.single_invoices.Form')
    @endif
</div>