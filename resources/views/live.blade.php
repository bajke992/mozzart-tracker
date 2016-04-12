@extends('layouts.app')

@section('content')
    <button onclick="send();">Click Me!</button>
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
            while(i--) { b[i] = this[i]; }
            return b;
        };

        Array.prototype.uniqueObjects = function () {
            var newArr = [];
            var unique = {};
            this.forEach(function (item) {
                if(!unique[item.matchId]){
                    newArr.push(item);
                    unique[item.matchId] = item;
                }
            });

            return newArr;
        };

        var $results = null;
        var $ls = null;

        $(document).ready(function () {
            populateData();
            setInterval(send, 300000);
        });

        function send() {
            $.ajax({
                url: '{{ URL::route('offer', ['','']) }}',
                success: function (data) {
                    $results = null;
                    $ls = null;
                    $results = (JSON.parse(data)).matches;
                    if($results.length === 0) return;
                    if(window.localStorage.length > 0) window.localStorage.removeItem('data');
                    checkLS();
                    populateData();
                    collectFinishedData();
                }
            });
        }

        function checkLS(){
            if(window.localStorage.length === 0){
                window.localStorage.setItem('data', JSON.stringify($results));
            }
            $ls = JSON.parse(window.localStorage.getItem('data'));

            $tmp = $ls.concat($results);
            $newLS = $tmp.uniqueObjects();

            window.localStorage.removeItem('data');
            window.localStorage.setItem('data', JSON.stringify($newLS));
        }

        function populateData(){
            if(window.localStorage.length === 0) send();
            $ls = JSON.parse(window.localStorage.getItem('data'));
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
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[0].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[1].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[2].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[3].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[4].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[5].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[6].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[7].value : '')
                        ).append(
                                $('<td/>').text((item.odds.length >= 5) ? item.odds[5].subgames[8].value : '')
                        )
                );
            });
        }

        function collectFinishedData(){
            $.ajax({
                url: '{{ URL::route('home') }}',
                success: function (){
                    console.log('successFinishedData');
                }
            })
        }


    </script>
@endsection