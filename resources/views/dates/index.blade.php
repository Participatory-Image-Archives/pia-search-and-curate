@extends('base')

@section('content')
<div class="p-4">
    <div class="mb-10">
        <h2 class="text-2xl md:w-1/2">
            Dates
        </h2>
        
        <table class="table">
            <tr>
                <td>Images without a date: </td>
                <td>{{ number_format($images_wo, 0, '.', '\'') }} <span class="text-xs">({{ number_format(100 / $images * $images_wo, 2) }}%)</span></td>
            </tr>
            <tr>
                <td>Images with accuracy to the day: </td>
                <td>{{ number_format($images_w_acc_1, 0, '.', '\'') }} <span class="text-xs">({{ number_format(100 / $images * $images_w_acc_1, 2) }}%)</td>
            </tr>
            <tr>
                <td>Images with accuracy to the month: </td>
                <td>{{ number_format($images_w_acc_2, 0, '.', '\'') }} <span class="text-xs">({{ number_format(100 / $images * $images_w_acc_2, 2) }}%)</td>
            </tr>
            <tr>
                <td>Images with accuracy to the year: </td>
                <td>{{ number_format($images_w_acc_3, 0, '.', '\'') }} <span class="text-xs">({{ number_format(100 / $images * $images_w_acc_3, 2) }}%)</td>
            </tr>
            <tr>
                <td>Images with date ranges: </td>
                <td>{{ number_format($images_w_date_range, 0, '.', '\'') }} <span class="text-xs">({{ number_format(100 / $images * $images_w_date_range, 2) }}%)</td>
            </tr>
            <tr>
                <td>Total images count: </td>
                <td class="underline">{{ number_format($images, 0, '.', '\'') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection