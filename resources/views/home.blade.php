@extends('layouts.app')

@section('content')
    <table>
        <thead>
            <th>
                Match count
            </th>
            @foreach($results[0]->odds as $odd)
                <th>
                    {{ $odd->name }}
                </th>
            @endforeach
        </thead>
        <tbody>
            @foreach($results as $result)
                <tr>
                    <td>
                        {{ $result->count }}
                    </td>
                    @foreach($result->odds as $odd)
                        <td>
                            {{ $odd->value }}
                            | {{ $odd->win_count }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
