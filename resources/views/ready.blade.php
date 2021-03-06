@extends('layouts.app')

@section('content')
    <button onclick="send();">Ready</button>
    <input type="text" id="page" placeholder="Page">
    <button onclick="collectFinishedData();">Finished</button>

    {{--<button style="float: right;" onclick="sendFinished();">Send</button>--}}
    <table border="1">

    </table>
@endsection

@section('js')
    <script>
        Array.prototype.remove = function () {
            var what, a = arguments, L = a.length, ax;
            while (L && this.length) {
                what = a[--L];
                while ((ax = this.indexOf(what)) !== -1) {
                    this.splice(ax, 1);
                }
            }
            return this;
        };

        Array.prototype.clone = function () {
            var b = new Array(this.length);
            var i = this.length;
            while (i--) {
                b[i] = this[i];
            }
            return b;
        };

        Array.prototype.uniqueObjects = function () {
            var newArr = [];
            var unique = {};
            this.forEach(function (item) {
                if (!unique[item.matchId]) {
                    newArr.push(item);
                    unique[item.matchId] = item;
                }
            });

            return newArr;
        };

        var $results = null;
        var $ls = null;
        var $page = 1;

        $(document).ready(function () {
            populateData();
        });

        var interval = true;

        function send() {
            if(interval) {
                setInterval(send, 300000);
                interval = false;
            }
            if($('#page').val() !== "") $page = $('#page').val();
            $.ajax({
                url: '{{ URL::route('offer.ready', ['','']) }}pageNumber=' + $page,
                success: function (data) {
                    $results = null;
                    $results = (JSON.parse(data)).matches;
                    if ($results.length === 0) return;
                    $ls = null;
                    if(location.search === "") {
                        if (window.localStorage.hasOwnProperty('ready')) window.localStorage.removeItem('ready');
                    }
                    checkLS();
                    populateData();
//                    collectFinishedData();
                }
            });
        }

        function checkLS() {
            if (!window.localStorage.hasOwnProperty('ready')) {
                window.localStorage.setItem('ready', JSON.stringify($results));
            }
            $ls = JSON.parse(window.localStorage.getItem('ready'));

            $tmp = $ls.concat($results);
            $newLS = $tmp.uniqueObjects();

            window.localStorage.removeItem('ready');
            window.localStorage.setItem('ready', JSON.stringify($newLS));
        }

        function populateData() {
            if (!window.localStorage.hasOwnProperty('ready')) send();
            $ls = JSON.parse(window.localStorage.getItem('ready'));
            $table = $('table');
            $table.children().remove().delay(2000);

            $table.append(
                    $('<tr/>').append(
                            $('<th/>').text('Vreme')
                    ).append(
                            $('<th/>').text('Minut')
                    ).append(
                            $('<th/>').text('Liga')
                    ).append(
                            $('<th/>').text('Domacin')
                    ).append(
                            $('<th/>').text('Gost')
                    ).append(
                            $('<th/>').text('Rezultat')
                    ).append(
                            $('<th/>').text('1')
                    ).append(
                            $('<th/>').text('X')
                    ).append(
                            $('<th/>').text('2')
                    ).append(
                            $('<th/>').text('1X')
                    ).append(
                            $('<th/>').text('12')
                    ).append(
                            $('<th/>').text('X2')
                    ).append(
                            $('<th/>').text('1-1')
                    ).append(
                            $('<th/>').text('1-X')
                    ).append(
                            $('<th/>').text('1-2')
                    ).append(
                            $('<th/>').text('X-1')
                    ).append(
                            $('<th/>').text('X-X')
                    ).append(
                            $('<th/>').text('X-2')
                    ).append(
                            $('<th/>').text('2-1')
                    ).append(
                            $('<th/>').text('2-X')
                    ).append(
                            $('<th/>').text('2-2')
                    )
            );

            $ls.forEach(function (item) {
                console.log(item);
                $date = new Date(item.time);
                $hours = ($date.getHours() < 10) ? "0" + $date.getHours() : $date.getHours();
                $minutes = ($date.getMinutes() < 10) ? "0" + $date.getMinutes() : $date.getMinutes();
                $table.append(
                        $('<tr/>').append(
                                $('<td/>').text($hours + ":" + $minutes)
                        ).append(
                                $('<td/>').text(item.minute)
                        ).append(
                                $('<td/>').text(item.competition.shortName)
                        ).append(
                                $('<td/>').text(item.home)
                        ).append(
                                $('<td/>').text(item.visitor)
                        ).append(
                                $('<td/>').text(item.result)
                        ).append(
                                $('<td/>').text(item.odds[0].subgames[0].value)
                        ).append(
                                $('<td/>').text(item.odds[0].subgames[1].value)
                        ).append(
                                $('<td/>').text(item.odds[0].subgames[2].value)
                        ).append(
                                $('<td/>').text(item.odds[1].subgames[0].value)
                        ).append(
                                $('<td/>').text(item.odds[1].subgames[1].value)
                        ).append(
                                $('<td/>').text(item.odds[1].subgames[2].value)
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[0].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[1].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[2].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[3].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[4].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[5].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[6].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[7].value : '')
                        ).append(
                                $('<td/>').text((item.odds.hasOwnProperty(4)) ? item.odds[4].subgames[8].value : '')
                        )
                );
            });
        }

        function collectFinishedData() {
            $.ajax({
                url: '{{ URL::route('home') }}',
                success: function () {
                    console.log('successFinishedData');
                }
            })
        }

        var sendData = {
            matchId: 1,
            odds: [
                {
                    id: 1,
                    subgames: [
                        {
                            id: 1,
                            bettingGameId: 1,
                            value: "1,35",
                            winStatus: "LOSE"
                        },
                        {
                            id: 2,
                            bettingGameId: 1,
                            value: "4,40",
                            winStatus: "LOSE"
                        },
                        {
                            id: 3,
                            bettingGameId: 1,
                            value: "9,00",
                            winStatus: "LOSE"
                        }]
                },
                {
                    id: 2,
                    subgames: [
                        {
                            id: 1,
                            bettingGameId: 2,
                            value: "1,03",
                            winStatus: "LOSE"
                        },
                        {
                            id: 2,
                            bettingGameId: 2,
                            value: "1,17",
                            winStatus: "LOSE"
                        },
                        {
                            id: 3,
                            bettingGameId: 2,
                            value: "2,95",
                            winStatus: "LOSE"
                        }
                    ]
                },
                {
                    id: 3,
                    subgames: []
                },
                {
                    id: 4,
                    subgames: []
                },
                {
                    id: 5,
                    subgames: [
                        {
                            id: 1,
                            bettingGameId: 5,
                            value: "1,95",
                            winStatus: "LOSE"
                        },
                        {
                            id: 2,
                            bettingGameId: 5,
                            value: "23,0",
                            winStatus: "LOSE"
                        },
                        {
                            id: 3,
                            bettingGameId: 5,
                            value: "70,0",
                            winStatus: "LOSE"
                        },
                        {
                            id: 4,
                            bettingGameId: 5,
                            value: "3,90",
                            winStatus: "LOSE"
                        },
                        {
                            id: 5,
                            bettingGameId: 5,
                            value: "6,50",
                            winStatus: "LOSE"
                        },
                        {
                            id: 6,
                            bettingGameId: 5,
                            value: "18,0",
                            winStatus: "LOSE"
                        },
                        {
                            id: 7,
                            bettingGameId: 5,
                            value: "28,0",
                            winStatus: "LOSE"
                        },
                        {
                            id: 8,
                            bettingGameId: 5,
                            value: "23,0",
                            winStatus: "LOSE"
                        },
                        {
                            id: 9,
                            bettingGameId: 5,
                            value: "17,0",
                            winStatus: "LOSE"
                        }
                    ]
                }
            ]
        };

        function collectData() {
            $matchId = $('#matchId').data('id');
            var $a = $.extend(true, {}, sendData);

            $a.matchId = $matchId;

            $('input[type=checkbox]').each(function () {
                $item = $(this);

                switch ($item.attr('id')) {
                    case '1':
                    {
                        $a.odds[0].subgames[0].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[0].subgames[0].winStatus = "WIN";
                        break;
                    }
                    case 'X':
                    {
                        $a.odds[0].subgames[1].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[0].subgames[1].winStatus = "WIN";
                        break;
                    }
                    case '2':
                    {
                        $a.odds[0].subgames[2].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[0].subgames[2].winStatus = "WIN";
                        break;
                    }
                    case '1X':
                    {
                        $a.odds[1].subgames[0].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[1].subgames[0].winStatus = "WIN";
                        break;
                    }
                    case '12':
                    {
                        $a.odds[1].subgames[1].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[1].subgames[1].winStatus = "WIN";
                        break;
                    }
                    case 'X2':
                    {
                        $a.odds[1].subgames[2].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[1].subgames[2].winStatus = "WIN";
                        break;
                    }
                    case '1-1':
                    {
                        $a.odds[4].subgames[0].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[0].winStatus = "WIN";
                        break;
                    }
                    case '1-X':
                    {
                        $a.odds[4].subgames[1].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[1].winStatus = "WIN";
                        break;
                    }
                    case '1-2':
                    {
                        $a.odds[4].subgames[2].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[2].winStatus = "WIN";
                        break;
                    }
                    case 'X-1':
                    {
                        $a.odds[4].subgames[3].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[3].winStatus = "WIN";
                        break;
                    }
                    case 'X-X':
                    {
                        $a.odds[4].subgames[4].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[4].winStatus = "WIN";
                        break;
                    }
                    case 'X-2':
                    {
                        $a.odds[4].subgames[5].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[5].winStatus = "WIN";
                        break;
                    }
                    case '2-1':
                    {
                        $a.odds[4].subgames[6].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[6].winStatus = "WIN";
                        break;
                    }
                    case '2-X':
                    {
                        $a.odds[4].subgames[7].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[7].winStatus = "WIN";
                        break;
                    }
                    case '2-2':
                    {
                        $a.odds[4].subgames[8].value = $item.attr('name');
                        if ($item.is(':checked')) $a.odds[4].subgames[8].winStatus = "WIN";
                        break;
                    }
                }
            });

            return $a;
        }

        function sendFinished() {
            var $a = collectData();

            xhr = new XMLHttpRequest();
            fd = new FormData();

            fd.append('data', JSON.stringify($a));
            fd.append('_token', '{{ csrf_token() }}');

            xhr.open('POST', '{{ URL::route('finished') }}');
            xhr.send(fd);

            $ls.shift();
            window.localStorage.setItem('ready', JSON.stringify($ls));
            populateData();
        }


    </script>
@endsection