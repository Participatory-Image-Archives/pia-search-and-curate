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
                <td>{{ $images_wo }}</td>
            </tr>
            <tr>
                <td>Images with accuracy to the day: </td>
                <td>{{ $images_w_acc_1 }}</td>
            </tr>
            <tr>
                <td>Images with accuracy to the month: </td>
                <td>{{ $images_w_acc_2 }}</td>
            </tr>
            <tr>
                <td>Images with accuracy to the year: </td>
                <td>{{ $images_w_acc_3 }}</td>
            </tr>
            <tr>
                <td>Images with date ranges: </td>
                <td>{{ $images_w_date_range }}</td>
            </tr>
            <tr>
                <td>Total images count: </td>
                <td class="underline">{{ $images }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection